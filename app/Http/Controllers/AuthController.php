<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Mail\PasswordResetMail;
use App\Models\BonusHistory;
use App\Models\PasswordReset;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    public function showLoginForm(Request $request)
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        $credentials['is_active'] = true;

        if (Auth::attempt($credentials, $request->filled('remember'))) {

            $request->session()->regenerate();

            return redirect()->route('user.dashboard');
        }

        return back()
            ->withInput()
            ->with(['error' => 'Invalid credentials or inactive account']);
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {

        // Referral Code & Bonus (Optional)
        $referralDetails = $this->getBonus($request);

        // If referral code is invalid, return the error response
        if (isset($referralDetails['error'])) {
            return back()
                ->withInput()
                ->with('error', $referralDetails['error']);
        }

        try {
            // Start a database transaction
            DB::beginTransaction();
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'password' => Hash::make($request->password),
                'referral_code' => $referralDetails['myOwnCode'],
                'referred_by' => $referralDetails['referral_id'],
                'kyc_status' => 'Verified',
            ]);
            // Commit the transaction
            DB::commit();


            // Add bonus to the referring user (if applicable)
            if ($referralDetails['referral_id']) {
                $this->addBonus($referralDetails['referral_id'], $referralDetails['referral_bonus'], $user->id);
            }

            return redirect()->route('auth.login')
                ->with('success', 'Your account was created successfully. Login to proceed');
        } catch (\Exception $e) {


            DB::rollBack();

            Log::error('Registration error: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Registration failed. Please try again later.');
        }
    }

    private function getBonus($request)
    {
        $referral_id = null;
        $referral_bonus = 0.00;

        // Check if the referral code is provided and not empty
        if ($request->filled('referral_code')) {
            // Find the user with the provided referral code
            $referralUser = User::where('referral_code', $request->referral_code)->first();

            if ($referralUser) {
                // Valid referral code found
                $referral_id = $referralUser->id;
                $referral_bonus = $referralUser->referral_bonus;

                // If the referral bonus is 0, use the default bonus from the referral_bonus table
                if (bccomp($referral_bonus, '0.00', 2) === 0) {
                    $defaultBonus = DB::table('referral_bonus')->value('bonus');
                    if ($defaultBonus !== null) {
                        $referral_bonus = $defaultBonus;
                    }
                }
            } else {
                // Invalid referral code
                return ['error' => 'Invalid Referral Code. Please enter a valid one to proceed.'];
            }
        }

        // Generate a unique referral code for the current user
        do {
            $random = md5(uniqid($request->email, true));
            $myOwnCode = substr($random, 0, 6);
        } while (User::where('referral_code', $myOwnCode)->exists());

        return [
            'referral_id' => $referral_id,
            'referral_bonus' => $referral_bonus,
            'myOwnCode' => $myOwnCode,
        ];
    }

    private function addBonus($referral_id, $referral_bonus, $referred_user_id)
    {
        // Check if the referring user has a wallet
        $exist = User::where('id', $referral_id)
            ->where('wallet_is_created', 1)
            ->exists();

        if ($exist) {
            // Get the referring user's bonus record
            $wallet = Wallet::where('user_id', $referral_id)->first();

            if ($wallet) {
                // Update the bonus balance
                $bonus_balance = $wallet->bonus + $referral_bonus;

                Wallet::where('user_id', $referral_id)->update([
                    'bonus' => $bonus_balance,
                ]);

                // Log the bonus history
                BonusHistory::create([
                    'user_id' => $referral_id,
                    'referred_user_id' => $referred_user_id,
                    'amount' => $referral_bonus,
                    'type' => 'referral',
                ]);
            }
        }
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            Session::flash('error', 'No user found with that email address');
            return redirect()->back();
        }
        $string = Str::random(100);

        $password = PasswordReset::updateOrCreate(['email' => $request->email], [
            'email' => $request->email,
            'token' => $string,
            'created_at' => Carbon::now()
        ]);

        $link = route('auth.password.reset', ['token' => $string]);

        $user_name = $user->name ?? 'User';

        Mail::to($user->email)->send(new PasswordResetMail($link, $user_name));

        Session::flash('success', 'Password reset link sent to the mail provided');

        return redirect()->route('auth.password.request');
    }

    public function showResetPasswordForm(Request $request, $token)
    {


        $reset = PasswordReset::where('token', $token)->first();

        if (!$reset) {
            Session::flash('error', 'Invalid token reset password token');

            return view('auth.reset-password');
        }

        $time = Carbon::now()->diffInMinutes($reset->created_at);

        if ($time > 30) {
            Session::flash('error', 'Token expired please request again');

            return view('auth.reset-password');
        }

        return view('auth.reset-password')->with('token', $token);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed'],
            'token' => ['required', 'exists:password_reset_tokens,token']
        ]);

        $password = PasswordReset::where('token', $request->token)->first();

        if (!$password) {
            Session::flash('error', 'Reset request not found');

            return redirect()->back();
        }
        $user = User::where('email', $password->email)->first();

        if (!$user) {
            Session::flash('error', 'User not found');

            return redirect()->back();
        }

        $user->password = Hash::make($request->password);

        $user->update();

        $password->delete();

        Session::flash('success', 'Password successfuly reset please login');

        return redirect()->route('auth.login');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('auth.login');
    }
}
