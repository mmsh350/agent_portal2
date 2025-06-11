<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use App\Services\TransactionService;
use Illuminate\Http\Request;


class UserController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }
    public function userCheck()
    {

        if (auth()->user()->role != 'super admin')
            abort(403, 'Unauthorized');
    }
    public function index(Request $request)
    {

        $this->userCheck();

        $query = User::query()->ExcludeAdmin();

        $allUsers = User::count();
        $active = User::where('is_active', true)->count();
        $notActive = User::where('is_active', false)->count();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%$search%")
                    ->orWhere('name', 'like', "%$search%")
                    ->orWhere('role', 'like', "%$search%")
                    ->orWhere('referral_code', 'like', "%$search%")
                    ->orWhere('phone_number', 'like', "%$search%");
            });
        }

        $perPage = $request->input('per_page', 10);
        $users = $query->paginate($perPage)->withQueryString();

        return view('admin.users.index', compact('users', 'allUsers', 'active', 'notActive'));
    }

    public function show(User $user)
    {
        $this->userCheck();

        $transactions = Transaction::where('user_id', $user->id)->latest()->limit(10)->get();

        return view('admin.users.show', compact('user', 'transactions'));
    }

    public function edit(User $user)
    {
        $this->userCheck();
        return view('admin.users.edit', compact('user'));
    }

    public function activate(User $user)
    {
        $this->userCheck();

        $user->is_active = !$user->is_active;
        $user->save();

        return back()->with('success', 'User activation status updated.');
    }

    public function update(Request $request, User $user)
    {
        $this->userCheck();

        $request->validate([
            'name' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'email' => 'nullable|email',
            'role' => 'nullable|in:user,admin,agent',
            'referral_code' => 'nullable|string',
            'referral_bonus' => 'nullable|numeric',
            'wallet_balance' => 'nullable|numeric',
            // 'profile_pic' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if (!is_null($request->wallet_balance)) {
            $request->validate([
                'topup_type' => 'required|numeric|in:1,2',
            ]);
        }

        $user->fill($request->only([
            'name',
            'phone_number',
            'email',
            'role',
            'referral_code',
            'referral_bonus'
        ]));

        // Convert image to base64 if uploaded
        if ($request->hasFile('profile_pic')) {
            $image = $request->file('profile_pic');
            $base64Image = base64_encode(file_get_contents($image->getRealPath()));
            $user->profile_pic = $base64Image;
        }

        // Wallet balance update
        if ($request->wallet_balance) {
            $amount = $request->wallet_balance;

            if ($user->wallet) {


                if ($request->topup_type == 1) {
                    $user->wallet->balance += $amount;
                    $user->wallet->deposit += $amount;
                    $topuptype = 'credited';
                } else {
                    if ($amount < $user->wallet) {
                        //do nothing
                    } else {
                        $user->wallet->balance -= $amount;
                        $user->wallet->deposit -= $amount;
                        $topuptype = 'debited';
                    }
                }
                $serviceDesc = 'Wallet ' . $topuptype . ' with a service fee of ₦' . number_format($amount, 2);
                $user->wallet->save();
                $this->transactionService->createTransaction($user->id, $amount, 'Admin Top Up',   $serviceDesc,  'Wallet', 'Approved');
            }
        }

        $user->save();

        return redirect()->route('admin.user.edit', compact('user'))->with('success', 'User updated successfully.');
    }
}
