<?php

namespace App\Http\Controllers;

use App\Http\Repositories\NIN_PDF_Repository;
use App\Http\Repositories\BVN_PDF_Repository;
use App\Http\Repositories\VirtualAccountRepository;
use App\Http\Repositories\WalletRepository;
use App\Models\BvnPhoneSearch;
use App\Models\IpeRequest;
use App\Models\NinValidation;
use App\Models\Service;
use App\Models\Verification;
use App\Models\Wallet;
use App\Services\TransactionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VerificationController extends Controller
{

    protected $transactionService;
    protected $loginId;

    const RESP_STATUS_SUCCESS = true;
    const RESP_MESSAGE = null;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
        $this->loginId = auth()->user()->id;
    }

    public function ShowIpe()
    {
        $serviceCodes = ['112'];
        $services = Service::whereIn('service_code', $serviceCodes)
            ->get()
            ->keyBy('service_code');

        // Extract specific service fees
        $ServiceFee = $services->get('112') ?? 0.00;

        $ipes = IpeRequest::where('user_id',  $this->loginId)
            ->orderBy('id', 'desc')
            ->paginate(5);


        return view('verification.ipe', compact('ServiceFee',  'ipes'));
    }
    public function showNinValidation()
    {
        $serviceCodes = ['114'];
        $services = Service::whereIn('service_code', $serviceCodes)
            ->get()
            ->keyBy('service_code');

        $ServiceFee = $services->get('114') ?? 0.00;

        $validations = NinValidation::where('user_id',  $this->loginId)
            ->orderBy('id', 'desc')
            ->paginate(5);


        return view('verification.nin-validation', compact('ServiceFee',  'validations'));
    }

    public function bvnPhoneSearch()
    {
        $serviceCodes = ['115'];
        $services = Service::whereIn('service_code', $serviceCodes)
            ->get()
            ->keyBy('service_code');

        $ServiceFee = $services->get('115') ?? 0.00;

        $bvns = BvnPhoneSearch::where('user_id',  $this->loginId)
            ->orderBy('id', 'desc')
            ->paginate(5);

        return view('verification.phone-search', compact('ServiceFee',  'bvns'));
    }
    public function ninPersonalize($auto = false)
    {
        $serviceCodes = ['108', '105', '106', '107'];
        $services = Service::whereIn('service_code', $serviceCodes)
            ->get()
            ->keyBy('service_code');

        // Extract specific service fees
        $ServiceFee = $services->get('108') ?? 0.00;
        $regular_nin_fee = $services->get('105') ?? 0.00;
        $standard_nin_fee = $services->get('106') ?? 0.00;
        $premium_nin_fee = $services->get('107') ?? 0.00;

        return view('verification.nin-track', compact('ServiceFee', 'regular_nin_fee', 'standard_nin_fee', 'premium_nin_fee', 'auto'));
    }

    public function ninVerify()
    {

        $serviceCodes = ['104', '106', '107', '105','116'];
        $services = Service::whereIn('service_code', $serviceCodes)
            ->get()
            ->keyBy('service_code');

        // Extract specific service fees
        $ServiceFee = $services->get('104') ?? 0.00;
        $standard_nin_fee = $services->get('106') ?? 0.00;
        $premium_nin_fee = $services->get('107') ?? 0.00;
        $regular_nin_fee = $services->get('105') ?? 0.00;
        $basic_nin_fee = $services->get('116') ?? 0.00;

        $user = auth()->user();

        $latestVerifications = $user->verifications()->latest()->paginate(5);

        return view('verification.nin-verify', compact('ServiceFee', 'standard_nin_fee', 'premium_nin_fee', 'regular_nin_fee','basic_nin_fee','latestVerifications'));
    }
    public function demoVerify()
    {

        $serviceCodes = ['113', '106', '107', '105','116'];
        $services = Service::whereIn('service_code', $serviceCodes)
            ->get()
            ->keyBy('service_code');

        // Extract specific service fees
        $ServiceFee = $services->get('113') ?? 0.00;
        $standard_nin_fee = $services->get('106') ?? 0.00;
        $premium_nin_fee = $services->get('107') ?? 0.00;
        $regular_nin_fee = $services->get('105') ?? 0.00;
        $basic_nin_fee = $services->get('116') ?? 0.00;

        $user = auth()->user();

        $latestVerifications = $user->verifications()->latest()->paginate(5);

        return view('verification.demo-verify', compact('ServiceFee', 'standard_nin_fee', 'premium_nin_fee', 'regular_nin_fee','basic_nin_fee','latestVerifications'));
    }
    public function bvnVerify()
    {
        // Fetch all required service fees in one query
        $serviceCodes = ['101', '102', '103', '109'];
        $services = Service::whereIn('service_code', $serviceCodes)->get()->keyBy('service_code');

        $BVNFee = $services->get('101') ?? 0.00;;
        $bvn_standard_fee = $services->get('102') ?? 0.00;
        $bvn_premium_fee = $services->get('103') ?? 0.00;
        $bvn_plastic_fee = $services->get('109') ?? 0.00;

        return view('verification.bvn-verify', compact('BVNFee', 'bvn_standard_fee', 'bvn_premium_fee', 'bvn_plastic_fee'));
    }
    public function phoneVerify()
    {

        $serviceCodes = ['111', '105', '106', '107','116'];
        $services = Service::whereIn('service_code', $serviceCodes)
            ->get()
            ->keyBy('service_code');

        // Extract specific service fees
        $ServiceFee = $services->get('111') ?? 0.00;
        $standard_nin_fee = $services->get('106') ?? 0.00;
        $regular_nin_fee = $services->get('105') ?? 0.00;
        $premium_nin_fee = $services->get('107') ?? 0.00;
        $basic_nin_fee = $services->get('116') ?? 0.00;

        $user = auth()->user();

        $latestVerifications = $user->verifications()->latest()->paginate(5);


        return view('verification.nin-phone-verify', compact('ServiceFee', 'standard_nin_fee', 'premium_nin_fee', 'regular_nin_fee','basic_nin_fee','latestVerifications'));
    }

    public function createAccounts()
    {

        $userId = auth()->user()->id;

        try {

            $repObj = new WalletRepository;
            $repObj->createWalletAccount($userId);

            $repObj2 = new VirtualAccountRepository;
            $checkStatus = $repObj2->createVirtualAccount($userId);

            if ($checkStatus == true) {

                return redirect()->back()->with('success', 'Account created successfully! Click "Add Fund" to deposit funds into your account.');
            } else {
                Log::error('Error Verifiying User ' . auth()->user()->id);
                return redirect()->back()->with('error', 'Account generation failed. Please try again or contact support for assistance.');
            }
        } catch (\Exception $e) {
            Log::error('Error Verifiying User ' . auth()->user()->id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went Wrong');
        }
    }

    public function ninRetrieve(Request $request)
    {

        $request->validate(
            ['nin' => 'required|numeric|digits:11'],
            [
                'nin.required' => 'The NIN number is required.',
                'nin.numeric' => 'The NIN number must be a numeric value.',
                'nin.digits' => 'The NIN must be exactly 11 digits.',
            ]
        );

        //NIN Services Fee
        $ServiceFee = 0;

        $ServiceFee = Service::where('service_code', '104')
            ->where('status', 'enabled')
            ->first();

        if (!$ServiceFee)
            return response()->json([
                'message' => 'Error',
                'errors' => ['Service Error' => 'Sorry Action not Allowed !'],
            ], 422);

        $ServiceFee = $ServiceFee->amount;

        $loginUserId = auth()->user()->id;

        //Check if wallet is funded
        $wallet = Wallet::where('user_id', $loginUserId)->first();
        $wallet_balance = $wallet->balance;
        $balance = 0;

        if ($wallet_balance < $ServiceFee) {
            return response()->json([
                'message' => 'Error',
                'errors' => ['Wallet Error' => 'Sorry Wallet Not Sufficient for Transaction !'],
            ], 422);
        } else {

            try {

                $data = [
                    'idNumber' => $request->input('nin'),
                    'idType' => 'nin',
                    'consent' => true,
                ];

                $url = env('BASE_URL_VERIFY_USER') . '/api/nin/index.php';
                $token = env('VERIFY_USER_TOKEN');

                $headers = [
                    'Accept: application/json, text/plain, */*',
                    'Content-Type: application/json',
                    "Authorization: Bearer $token",
                ];

                // Initialize cURL
                $ch = curl_init();

                // Set cURL options
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

                // Execute request
                $response = curl_exec($ch);

                // Check for cURL errors
                if (curl_errno($ch)) {
                    throw new \Exception('cURL Error: ' . curl_error($ch));
                }

                // Close cURL session
                curl_close($ch);

                $response = json_decode($response, true);

                //Log response
                Log::info('NIN Vericiation', $response);

                if (isset($response['status']) && $response['status'] === self::RESP_STATUS_SUCCESS && $response['message'] !== self::RESP_MESSAGE) {

                    $data = $response['message'];

                    $this->processResponseDataForNIN($data);

                    $balance = $wallet->balance - $ServiceFee;

                    Wallet::where('user_id', $loginUserId)
                        ->update(['balance' => $balance]);

                    $serviceDesc = 'Wallet debitted with a service fee of ₦' . number_format($ServiceFee, 2);

                    $this->transactionService->createTransaction($loginUserId, $ServiceFee, 'NIN Verification', $serviceDesc,  'Wallet', 'Approved');

                    return json_encode(['status' => 'success', 'data' => $data]);
                } else if (isset($response['status']) && $response['status'] === self::RESP_STATUS_SUCCESS && $response['message'] === self::RESP_MESSAGE) {

                    return response()->json([
                        'status' => 'Not Found',
                        'errors' => ['Succesfully Verified with ( NIN do not exist)'],
                    ], 422);
                }else if(isset($response['status']) && $response['status'] === 'caption'){

                    return response()->json([
                        'status' => 'Verification Failed',
                        'errors' => ['Caption: '.$response['message']],
                    ], 422);

                }else {
                    return response()->json([
                        'status' => 'Verification Failed',
                        'errors' => ['Verification Failed: No need to worry, your wallet remains secure and intact. Please try again or contact support for assistance.'],
                    ], 422);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'Request failed',
                    'errors' => ['An error occurred while making the API request'],
                ], 422);
            }
        }
    }

    public function ninDemoRetrieve(Request $request)
    {

        $request->validate([
            'gender' => ['required', 'in:MALE,FEMALE'],
            'dob' => ['required', 'date'],
            'lastName' => ['required', 'string', 'max:255'],
            'firstName' => ['required', 'string', 'max:255'],
        ]);

        //NIN Services Fee
        $ServiceFee = 0;

        $ServiceFee = Service::where('service_code', '113')
            ->where('status', 'enabled')
            ->first();

        if (!$ServiceFee)
            return response()->json([
                'message' => 'Error',
                'errors' => ['Service Error' => 'Sorry Action not Allowed !'],
            ], 422);

        $ServiceFee = $ServiceFee->amount;

        $loginUserId = auth()->user()->id;

        //Check if wallet is funded
        $wallet = Wallet::where('user_id', $loginUserId)->first();
        $wallet_balance = $wallet->balance;
        $balance = 0;

        if ($wallet_balance < $ServiceFee) {
            return response()->json([
                'message' => 'Error',
                'errors' => ['Wallet Error' => 'Sorry Wallet Not Sufficient for Transaction !'],
            ], 422);
        } else {

            try {

                $data = [
                    'idType' => 'doc',
                    'firstName' => $request->input('firstName'),
                    'lastName' => $request->input('lastName'),
                    'dob' => $request->input('dob'),
                    'gender' => $request->input('gender'),
                    'consent' => true,
                ];

                $url = env('BASE_URL_VERIFY_USER') . '/api/doc/index.php';
                $token = env('VERIFY_USER_TOKEN');

                $headers = [
                    'Accept: application/json, text/plain, */*',
                    'Content-Type: application/json',
                    "Authorization: Bearer $token",
                ];

                // Initialize cURL
                $ch = curl_init();

                // Set cURL options
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

                // Execute request
                $response = curl_exec($ch);

                // Check for cURL errors
                if (curl_errno($ch)) {
                    throw new \Exception('cURL Error: ' . curl_error($ch));
                }

                // Close cURL session
                curl_close($ch);

                $response = json_decode($response, true);

                //Log response
                Log::info('NIN DEMO Vericiation', $response);

                if (isset($response['status']) && $response['status'] === self::RESP_STATUS_SUCCESS && $response['message'] !== "norecord") {

                    $data = $response['message'];

                    $this->processResponseDataForNINDEMO($data);

                    $balance = $wallet->balance - $ServiceFee;

                    Wallet::where('user_id', $loginUserId)
                        ->update(['balance' => $balance]);

                    $serviceDesc = 'Wallet debitted with a service fee of ₦' . number_format($ServiceFee, 2);

                    $this->transactionService->createTransaction($loginUserId, $ServiceFee, 'NIN Verification', $serviceDesc,  'Wallet', 'Approved');

                    return json_encode(['status' => 'success', 'data' => $data]);
                } else if (isset($response['status']) && $response['status'] === self::RESP_STATUS_SUCCESS && $response['message'] === 'norecord') {

                    return response()->json([
                        'status' => 'Not Found',
                        'errors' => ['No record found'],
                    ], 422);
                }else if(isset($response['status']) && $response['status'] === 'caption'){

                    return response()->json([
                        'status' => 'Verification Failed',
                        'errors' => ['Caption: '.$response['message']],
                    ], 422);

                }else {
                    return response()->json([
                        'status' => 'Verification Failed',
                        'errors' => ['Verification Failed: No need to worry, your wallet remains secure and intact. Please try again or contact support for assistance.'],
                    ], 422);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'Request failed',
                    'errors' => ['An error occurred while making the API request'],
                ], 422);
            }
        }
    }

    public function ninPhoneRetrieve(Request $request)
    {

        $request->validate(
            ['nin' => 'required|numeric|digits:11'],
            [
                'nin.required' => 'The Phone number is required.',
                'nin.numeric' => 'The Phone number must be a numeric value.',
                'nin.digits' => 'The Phone must be exactly 11 digits.',
            ]
        );

        //NIN Services Fee
        $ServiceFee = 0;

        $ServiceFee = Service::where('service_code', '111')
            ->where('status', 'enabled')
            ->first();

        if (!$ServiceFee)
            return response()->json([
                'message' => 'Error',
                'errors' => ['Service Error' => 'Sorry Action not Allowed !'],
            ], 422);

        $ServiceFee = $ServiceFee->amount;

        $loginUserId = auth()->user()->id;

        //Check if wallet is funded
        $wallet = Wallet::where('user_id', $loginUserId)->first();
        $wallet_balance = $wallet->balance;
        $balance = 0;

        if ($wallet_balance < $ServiceFee) {
            return response()->json([
                'message' => 'Error',
                'errors' => ['Wallet Error' => 'Sorry Wallet Not Sufficient for Transaction !'],
            ], 422);
        } else {

            try {

                $data = [
                    'idNumber' => $request->input('nin'),
                    'idType' => 'pnv',
                    'consent' => true,
                ];

                $url = env('BASE_URL_VERIFY_USER') . '/api/pnv/index.php';
                $token = env('VERIFY_USER_TOKEN');

                $headers = [
                    'Accept: application/json, text/plain, */*',
                    'Content-Type: application/json',
                    "Authorization: Bearer $token",
                ];

                // Initialize cURL
                $ch = curl_init();

                // Set cURL options
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

                // Execute request
                $response = curl_exec($ch);

                // Check for cURL errors
                if (curl_errno($ch)) {
                    throw new \Exception('cURL Error: ' . curl_error($ch));
                }

                // Close cURL session
                curl_close($ch);


                $response = json_decode($response, true);

                  //Log response
                  Log::info('NIN Phone Vericiation:', $response);

                if (isset($response['status']) && $response['status'] === self::RESP_STATUS_SUCCESS && $response['message'] !== self::RESP_MESSAGE) {

                    $data = $response['message'];

                    $this->processResponseDataForNINPhone($data);

                    $balance = $wallet->balance - $ServiceFee;

                    Wallet::where('user_id', $loginUserId)
                        ->update(['balance' => $balance]);

                    $serviceDesc = 'Wallet debitted with a service fee of ₦' . number_format($ServiceFee, 2);

                    $this->transactionService->createTransaction($loginUserId, $ServiceFee, 'NIN Phone Verification', $serviceDesc,  'Wallet', 'Approved');

                    return json_encode(['status' => 'success', 'data' => $data]);
                } else if (isset($response['status']) && $response['status'] === self::RESP_STATUS_SUCCESS && $response['message'] === self::RESP_MESSAGE) {
                    return response()->json([
                        'status' => 'Not Found',
                        'errors' => ['Succesfully Verified with ( NIN do not exist)'],
                    ], 422);
                }else if(isset($response['status']) && $response['status'] === 'caption'){

                        return response()->json([
                            'status' => 'Verification Failed',
                            'errors' => ['Caption: '.$response['message']],
                        ], 422);

                 }else {
                    return response()->json([
                        'status' => 'Verification Failed',
                        'errors' => ['Verification Failed: No need to worry, your wallet remains secure and intact. Please try again or contact support for assistance.'],
                    ], 422);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'Request failed',
                    'errors' => ['An error occurred while making the API request'],
                ], 422);
            }
        }
    }

    public function ninValidation(Request $request)
    {
        $request->validate([
            'nin_number' => 'required|digits:11',
            'description' => 'required|string',
        ]);

        //NIN Services Fee
        $ServiceFee = 0;

        $ServiceFee = Service::where('service_code', '114')
            ->where('status', 'enabled')
            ->first();

        if (!$ServiceFee)
            return redirect()->route('user.nin-validation')
                ->with('error', 'Sorry Action not Allowed !');

        $ServiceFee = $ServiceFee->amount;

        $loginUserId = auth()->user()->id;

        //Check if wallet is funded
        $wallet = Wallet::where('user_id', $loginUserId)->first();
        $wallet_balance = $wallet->balance;
        $balance = 0;

        if ($wallet_balance < $ServiceFee) {

            return redirect()->route('user.nin-validation')
                ->with('error', 'Sorry Wallet Not Sufficient for Transaction !');
        } else {

            $balance = $wallet_balance - $ServiceFee;

            Wallet::where('user_id', $loginUserId)
            ->update(['balance' => $balance]);

           $serviceDesc = 'Wallet debitted with a service fee of ₦' . number_format($ServiceFee, 2);

           $trx_id = $this->transactionService->createTransaction($loginUserId, $ServiceFee, 'NIN Validation Request', $serviceDesc,  'Wallet', 'Approved');
           $refno = $this->transactionService->generateReferenceNumber();

           // Save NIN validation
            NinValidation::create([
                'user_id' => $loginUserId,
                'tnx_id' => $trx_id->id,
                'refno' => $refno,
                'nin_number'  => $request->nin_number,
                'description' => $request->description,
            ]);

            return redirect()->route('user.nin-validation')
            ->with('success', 'Validation submitted successfully !');

        }
    }

    public function bvnPhoneRequest(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|digits:11',
            'name' => 'required|string',
        ]);

        //NIN Services Fee
        $ServiceFee = 0;

        $ServiceFee = Service::where('service_code', '115')
            ->where('status', 'enabled')
            ->first();

        if (!$ServiceFee)
            return redirect()->route('user.bvn-phone-search')
                ->with('error', 'Sorry Action not Allowed !');

        $ServiceFee = $ServiceFee->amount;

        $loginUserId = auth()->user()->id;

        //Check if wallet is funded
        $wallet = Wallet::where('user_id', $loginUserId)->first();
        $wallet_balance = $wallet->balance;
        $balance = 0;

        if ($wallet_balance < $ServiceFee) {

            return redirect()->route('user.bvn-phone-search')
                ->with('error', 'Sorry Wallet Not Sufficient for Transaction !');
        } else {

            $balance = $wallet_balance - $ServiceFee;

            Wallet::where('user_id', $loginUserId)
            ->update(['balance' => $balance]);

           $serviceDesc = 'Wallet debitted with a service fee of ₦' . number_format($ServiceFee, 2);

           $trx_id = $this->transactionService->createTransaction($loginUserId, $ServiceFee, 'BVN Phone Search', $serviceDesc,  'Wallet', 'Approved');
           $refno = $this->transactionService->generateReferenceNumber();

           // Save NIN validation
            BvnPhoneSearch::create([
                'user_id' => $loginUserId,
                'tnx_id' => $trx_id->id,
                'refno' => $refno,
                'phone_number'  => $request->phone_number,
                'name' => $request->name,
            ]);

            return redirect()->route('user.bvn-phone-search')
            ->with('success', 'BVN Search submitted successfully !');

        }
    }

    public function ipeRequest(Request $request)
    {
        $request->validate([
            'trackingId' => 'required|alpha_num|size:15',
        ]);

        //NIN Services Fee
        $ServiceFee = 0;

        $ServiceFee = Service::where('service_code', '112')
            ->where('status', 'enabled')
            ->first();

        if (!$ServiceFee)
            return redirect()->route('user.ipe')
                ->with('error', 'Sorry Action not Allowed !');

        $ServiceFee = $ServiceFee->amount;

        $loginUserId = auth()->user()->id;

        //Check if wallet is funded
        $wallet = Wallet::where('user_id', $loginUserId)->first();
        $wallet_balance = $wallet->balance;
        $balance = 0;

        if ($wallet_balance < $ServiceFee) {

            return redirect()->route('user.ipe')
                ->with('error', 'Sorry Wallet Not Sufficient for Transaction !');
        } else {

            try {

                $data = [
                    'idNumber' => $request->input('trackingId'),
                    'consent' => true,
                ];

                $url = env('BASE_URL_VERIFY_USER') . '/api/ipeclearance/index.php';
                $token = env('VERIFY_USER_TOKEN');

                $headers = [
                    'Accept: application/json, text/plain, */*',
                    'Content-Type: application/json',
                    "Authorization: Bearer $token",
                ];

                // Initialize cURL
                $ch = curl_init();

                // Set cURL options
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

                // Execute request
                $response = curl_exec($ch);

                // Check for cURL errors
                if (curl_errno($ch)) {
                    throw new \Exception('cURL Error: ' . curl_error($ch));
                }

                // Close cURL session
                curl_close($ch);

                $response = json_decode($response, true);

                //Log response
                Log::info('IPE Request', $response);

                if (isset($response['status']) && $response['status'] === true) {

                    $this->processResponseDataIpe($loginUserId, $request->input('trackingId'));

                    $balance = $wallet->balance - $ServiceFee;

                    Wallet::where('user_id', $loginUserId)
                        ->update(['balance' => $balance]);

                    $serviceDesc = 'Wallet debitted with a service fee of ₦' . number_format($ServiceFee, 2);

                    $this->transactionService->createTransaction($loginUserId, $ServiceFee, 'IPE Request', $serviceDesc,  'Wallet', 'Approved');

                    return redirect()->route('user.ipe')
                        ->with('success', $response['message']);
                } elseif(isset($response['status']) && $response['status'] === false){
                    return redirect()->route('user.ipe')
                    ->with('error', $response['message']);
                }else {
                    return redirect()->route('user.ipe')
                        ->with('error', $response['message']);
                }
            } catch (\Exception $e) {
                return redirect()->route('user.ipe')
                    ->with('error', 'An error occurred while making the API request');
            }
        }
    }

    public function ipeRequestStatus($trackingId)
    {
        try {

            $data = [
                'idNumber' => $trackingId,
                'consent' => true,
            ];

            $url = env('BASE_URL_VERIFY_USER') . '/api/ipestatus/index.php';
            $token = env('VERIFY_USER_TOKEN');

            $headers = [
                'Accept: application/json, text/plain, */*',
                'Content-Type: application/json',
                "Authorization: Bearer $token",
            ];

            // Initialize cURL
            $ch = curl_init();

            // Set cURL options
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

            // Execute request
            $response = curl_exec($ch);

            // Check for cURL errors
            if (curl_errno($ch)) {
                throw new \Exception('cURL Error: ' . curl_error($ch));
            }

            // Close cURL session
            curl_close($ch);

            $response = json_decode($response, true);

             //Log response
            Log::info('IPE Status:', $response);


            if (isset($response['status']) && $response['status'] === true) {


                IpeRequest::where('trackingId', $trackingId)
                    ->where('user_id', auth()->user()->id)
                    ->update(['reply' => $response['reply'] ?? '']);

                return redirect()->route('user.ipe')
                    ->with('success', 'IPE request is successful, check the reply section');

            } elseif (isset($response['status']) && $response['status'] === false) {

                    if($response['message'] == "New"){
                        return redirect()->route('user.ipe')
                        ->with('error', 'Your request is still been processed!.' );
                    }else{
                         //process refund & NIN Services Fee
                    $ServiceFee = 0;

                    $ServiceFee = Service::where('service_code', '112')
                        ->where('status', 'enabled')
                        ->first();

                    if (!$ServiceFee)
                        return redirect()->route('user.ipe')
                            ->with('error', 'Sorry Action not Allowed !');

                    $ServiceFee = $ServiceFee->amount;

                    $wallet = Wallet::where('user_id',   $this->loginId)->first();

                    $balance = $wallet->balance + $ServiceFee;

                    // Check if already refunded
                    $refunded = IpeRequest::where('trackingId', $trackingId)
                        ->where('user_id',auth()->user()->id)
                        ->whereNull('refunded_at')
                        ->first();

                    if ($refunded) {
                        Wallet::where('user_id', $this->loginId)
                            ->update(['balance' => $balance]);

                        IpeRequest::where('trackingId', $trackingId)
                            ->where('user_id', auth()->user()->id)
                            ->update(['refunded_at' => Carbon::now(), 'reply' => $response['message']]);

                        $this->transactionService->createTransaction($this->loginId, $ServiceFee, 'IPE Refund', "IPE Refund for Tracking ID: {$trackingId}",  'Wallet', 'Approved');

                return redirect()->route('user.ipe')
                    ->with('error',  'IPE Request '.$response['message'].' A Refund has been processed.');
                }else{
                    return redirect()->route('user.ipe')
                    ->with('error',  'IPE Request '.' Already Refunded! .');
                }
                    }

            } else {
                return redirect()->route('user.ipe')
                    ->with('error', $response['message'] );
            }
        } catch (\Exception $e) {

            return redirect()->route('user.ipe')
                ->with('error', 'An error occurred while making the API request');
        }
    }

    public function bvnRetrieve(Request $request)
    {

        $request->validate(['bvn' => 'required|numeric|digits:11']);

        //BVN Services Fee
        $ServiceFee = 0;
        $ServiceFee = Service::where('service_code', '101')->where('status', 'enabled')->first();
        $ServiceFee = $ServiceFee->amount;

        if (!$ServiceFee)
            return response()->json([
                'message' => 'Error',
                'errors' => ['Service Error' => 'Sorry Action not Allowed !'],
            ], 422);



        $loginUserId = auth()->user()->id;

        //Check if wallet is funded
        $wallet = Wallet::where('user_id', $loginUserId)->first();
        $wallet_balance = $wallet->balance;
        $balance = 0;

        if ($wallet_balance < $ServiceFee) {
            return response()->json([
                'message' => 'Error',
                'errors' => ['Wallet Error' => 'Sorry Wallet Not Sufficient for Transaction !'],
            ], 422);
        } else {

            try {

                $data = [
                    'idNumber' => $request->input('bvn'),
                    'idType' => 'BVN',
                    'consent' => true,
                    'slipType' => 'standard',
                ];

                $url = env('BASE_URL_VERIFY_USER') . '/api/bvn/index.php';
                $token = env('VERIFY_USER_TOKEN');

                $headers = [
                    'Accept: application/json, text/plain, */*',
                    'Content-Type: application/json',
                    "Authorization: Bearer $token",
                ];

                // Initialize cURL
                $ch = curl_init();

                // Set cURL options
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

                // Execute request
                $response = curl_exec($ch);

                // Check for cURL errors
                if (curl_errno($ch)) {
                    throw new \Exception('cURL Error: ' . curl_error($ch));
                }

                // Close cURL session
                curl_close($ch);

                $response = json_decode($response, true);

                //Log response
                Log::info('BVN Vericiation', $response);

                if (isset($response['status'], $response['message']['status']) && $response['status'] === self::RESP_STATUS_SUCCESS && $response['message']['status'] === 'found') {

                    $data = $response['message'];

                    $this->processResponseDataForBVN($data);

                    $balance = $wallet->balance - $ServiceFee;

                    Wallet::where('user_id', $loginUserId)
                        ->update(['balance' => $balance]);

                    $serviceDesc = 'Wallet debitted with a service fee of ₦' . number_format($ServiceFee, 2);

                    $this->transactionService->createTransaction($loginUserId, $ServiceFee, 'BVN Verification', $serviceDesc,  'Wallet', 'Approved');

                    return json_encode(['status' => 'success', 'data' => $data]);
                } else if (isset($response['status'], $response['message']['status']) && $response['status'] === self::RESP_STATUS_SUCCESS && $response['message']['status'] === 'not_found') {

                    return response()->json([
                        'status' => 'Not Found',
                        'errors' => ['Succesfully Verified with ( BVN NO FOUND)'],
                    ], 422);
                } else {
                    return response()->json([
                        'status' => 'Verification Failed',
                        'errors' => ['Verification Failed: No need to worry, your wallet remains secure and intact. Please try again or contact support for assistance.'],
                    ], 422);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'Request failed',
                    'errors' => ['An error occurred while making the API request'],
                ], 422);
            }
        }
    }

    public function ninTrackRetrieve(Request $request)
    {

        $request->validate([
            'trackingId' => 'required|alpha_num|size:15',
        ]);

        //NIN Services Fee
        $ServiceFee = 0;

        $ServiceFee = Service::where('service_code', '108')
            ->where('status', 'enabled')
            ->first();

        if (!$ServiceFee)
            return response()->json([
                'message' => 'Error',
                'errors' => ['Service Error' => 'Sorry Action not Allowed !'],
            ], 422);

        $ServiceFee = $ServiceFee->amount;

        $loginUserId = auth()->user()->id;

        //Check if wallet is funded
        $wallet = Wallet::where('user_id', $loginUserId)->first();
        $wallet_balance = $wallet->balance;
        $balance = 0;

        if ($wallet_balance < $ServiceFee) {
            return response()->json([
                'message' => 'Error',
                'errors' => ['Wallet Error' => 'Sorry Wallet Not Sufficient for Transaction !'],
            ], 422);
        } else {

            try {

                $data = [
                    'idNumber' => $request->input('trackingId'),
                    'idType' => 'tracking',
                    'consent' => true,
                ];

                $url = env('BASE_URL_VERIFY_USER') . '/api/personalisation/index.php';
                $token = env('VERIFY_USER_TOKEN');

                $headers = [
                    'Accept: application/json, text/plain, */*',
                    'Content-Type: application/json',
                    "Authorization: Bearer $token",
                ];

                // Initialize cURL
                $ch = curl_init();

                // Set cURL options
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

                // Execute request
                $response = curl_exec($ch);

                // Check for cURL errors
                if (curl_errno($ch)) {
                    throw new \Exception('cURL Error: ' . curl_error($ch));
                }

                // Close cURL session
                curl_close($ch);

                $response = json_decode($response, true);

                //Log response
                Log::info('NIN Personalization', $response);

                if (isset($response['status']) && $response['status'] === self::RESP_STATUS_SUCCESS && $response['message'] !== self::RESP_MESSAGE) {

                    $data = $response['message'];

                    $this->processResponseDataForNINTracking($data);

                    $balance = $wallet->balance - $ServiceFee;

                    Wallet::where('user_id', $loginUserId)
                        ->update(['balance' => $balance]);

                    $serviceDesc = 'Wallet debitted with a service fee of ₦' . number_format($ServiceFee, 2);

                    $this->transactionService->createTransaction($loginUserId, $ServiceFee, 'NIN Personalize', $serviceDesc,  'Wallet', 'Approved');

                    return json_encode(['status' => 'success', 'data' => $data]);
                } else if (isset($response['message'])) {

                    return response()->json([
                        'status' => 'Not Found',
                        'errors' => [$response['message']],
                    ], 422);
                } else {
                    return response()->json([
                        'status' => 'Verification Failed',
                        'errors' => ['Verification Failed: No need to worry, your wallet remains secure and intact. Please try again or contact support for assistance.'],
                    ], 422);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'Request failed',
                    'errors' => ['An error occurred while making the API request'],
                ], 422);
            }
        }
    }

    public function processResponseDataForNINDEMO($data)
    {

        try {
            Verification::create([
                'user_id' => auth()->user()->id,
                'idno' => $data['idNumber'],
                'type' => 'NIN',
                'nin' => $data['idNumber'],
                'trackingId' => $data['trackingId'],
                'first_name' => $data['firstName'],
                'middle_name' => $data['middleName'],
                'last_name' => $data['lastName'],
                'phoneno' => $data['mobile'],
                'dob' => \Carbon\Carbon::createFromFormat('d-m-Y', $data['dateOfBirth'])->format('Y-m-d'),
                'gender' => $data['gender'] == 'm' || $data['gender'] == 'Male' ? 'Male' : 'Female',
                'state' => $data['self_origin_state'],
                'lga' => $data['self_origin_lga'],
                'town' => $data['self_origin_place'],
                'address' => $data['addressLine'],
                'photo' => $data['photo'],
                'signature' => $data['signature'],
                'residence_state' => $data['residence_state'],
                'residence_lga' => $data['residence_lga'],

            ]);
        } catch (\Exception $e) {

            Log::error('Verification creation failed: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Failed to create verification record.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function processResponseDataForNIN($data)
    {

        try {
            $user = Verification::create([
                'user_id' => auth()->user()->id,
                'idno' => $data['nin'],
                'type' => 'NIN',
                'nin' => $data['nin'],
                'trackingId' => $data['trackingId'],
                'first_name' => $data['firstname'],
                'middle_name' => $data['middlename'],
                'last_name' => $data['surname'],
                'phoneno' => $data['telephoneno'],
                'dob' => \Carbon\Carbon::createFromFormat('d-m-Y', $data['birthdate'])->format('Y-m-d'),
                'gender' => $data['gender'] == 'm' || $data['gender'] == 'Male' ? 'Male' : 'Female',
                'state' => $data['self_origin_state'],
                'lga' => $data['self_origin_lga'],
                'residence_state' => $data['residence_state'],
                'residence_lga' => $data['residence_lga'],
                'residence_town' => $data['residence_town'],
                'town' => $data['self_origin_place'],
                'address' => $data['residence_AdressLine1'],
                'photo' => $data['image'],
                'signature' => $data['signature'],
            ]);
        } catch (\Exception $e) {

            Log::error('Verification creation failed: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Failed to create verification record.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function processResponseDataForBVN($data)
    {
        $user = Verification::create(
            [
                'idno' => $data['idNumber'],
                'type' => 'BVN',
                'nin' => $data['nin'],
                'first_name' => $data['firstName'],
                'middle_name' => $data['middleName'],
                'last_name' => $data['lastName'],
                'phoneno' => $data['mobile'],
                'dob' => $data['dateOfBirth'],
                'gender' => $data['gender'],
                'photo' => $data['image'],
                'enrollment_branch' => $data['enrollmentBranch'],
                'enrollment_bank' => $data['enrollmentInstitution'],
            ]
        );
    }

    public function processResponseDataForNINTracking($data)
    {

        try {
            Verification::create([
                'idno' => $data['nin'],
                'type' => 'NIN',
                'nin' => $data['nin'],
                'trackingId' => $data['trackingid'],
                'first_name' => $data['firstname'],
                'middle_name' => $data['middlename'],
                'last_name' => $data['lastname'],
                'dob' => '1970-01-01',
                'gender' => $data['gender'] == 'm' || $data['gender'] == 'Male' ? 'Male' : 'Female',
                'state' => $data['state'],
                'lga' => $data['town'],
                'address' => $data['address'],
                'photo' => $data['face'],
            ]);
        } catch (\Exception $e) {

            Log::error('Verification creation failed: ' . $e->getMessage());
        }
    }

    public function processResponseDataForNINPhone($data)
    {

        try {
            $user = Verification::create([
                'user_id' => auth()->user()->id,
                'idno' => $data['nin'],
                'type' => 'NIN',
                'nin' => $data['nin'],
                'trackingId' => $data['trackingId'],
                'first_name' => $data['firstname'],
                'middle_name' => $data['middlename'],
                'last_name' => $data['surname'],
                'phoneno' => $data['telephoneno'],
                'dob' => \Carbon\Carbon::createFromFormat('d-m-Y', $data['birthdate'])->format('Y-m-d'),
                'gender' => $data['gender'] == 'm' || $data['gender'] == 'Male' ? 'Male' : 'Female',
                'state' => $data['self_origin_state'],
                'lga' => $data['self_origin_lga'],
                'town' => $data['self_origin_place'],
                'residence_state' => $data['residence_state'],
                'residence_lga' => $data['residence_lga'],
                'residence_town' => $data['residence_town'],
                'address' => $data['residence_AdressLine1'],
                'photo' => $data['image'],
                'signature' => $data['signature'],

            ]);
        } catch (\Exception $e) {

            Log::error('Verification creation failed: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Failed to create verification record.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function processResponseDataIpe($userId, $trackingNo)
    {
        try {
            IpeRequest::create([
                'user_id' => $userId,
                'trackingId' => $trackingNo,
            ]);
        } catch (\Exception $e) {

            Log::error('Request creation failed: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Failed to create Ipe Request.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function regularSlip($nin_no)
    {

        //NIN Services Fee
        $ServiceFee = 0;
        $ServiceFee = Service::where('service_code', '105')->first();
        $ServiceFee = $ServiceFee->amount;

        //Check if wallet is funded
        $wallet = Wallet::where('user_id', $this->loginId)->first();
        $wallet_balance = $wallet->balance;
        $balance = 0;

        if ($wallet_balance  < $ServiceFee) {
            return response()->json([
                "message" => "Error",
                "errors" => array("Wallet Error" => "Sorry Wallet Not Sufficient for Transaction !")
            ], 422);
        } else {
            $balance = $wallet->balance - $ServiceFee;

            $affected = Wallet::where('user_id', $this->loginId)
                ->update(['balance' => $balance]);

            $serviceDesc = 'Wallet debitted with a service fee of ₦' . number_format($ServiceFee, 2);

            $this->transactionService->createTransaction($this->loginId, $ServiceFee, 'Regular NIN Slip', $serviceDesc,  'Wallet', 'Approved');

            //Generate PDF
            $repObj = new NIN_PDF_Repository();
            $response = $repObj->regularPDF($nin_no);
            return  $response;
        }
    }

    public function standardSlip($nin_no)
    {

        //NIN Services Fee
        $ServiceFee = 0;
        $ServiceFee = Service::where('service_code', '106')->first();
        $ServiceFee = $ServiceFee->amount;

        //Check if wallet is funded
        $wallet = Wallet::where('user_id', $this->loginId)->first();
        $wallet_balance = $wallet->balance;
        $balance = 0;

        if ($wallet_balance  < $ServiceFee) {
            return response()->json([
                "message" => "Error",
                "errors" => array("Wallet Error" => "Sorry Wallet Not Sufficient for Transaction !")
            ], 422);
        } else {
            $balance = $wallet->balance - $ServiceFee;

            $affected = Wallet::where('user_id', $this->loginId)
                ->update(['balance' => $balance]);

            $serviceDesc = 'Wallet debitted with a service fee of ₦' . number_format($ServiceFee, 2);

            $this->transactionService->createTransaction($this->loginId, $ServiceFee, 'Standard NIN Slip', $serviceDesc,  'Wallet', 'Approved');

            //Generate PDF
            $repObj = new NIN_PDF_Repository();
            $response = $repObj->standardPDF($nin_no);
            return  $response;
        }
    }

    public function premiumSlip($nin_no)
    {
        //NIN Services Fee
        $ServiceFee = 0;
        $ServiceFee = Service::where('service_code', '107')->first();
        $ServiceFee = $ServiceFee->amount;

        //Check if wallet is funded
        $wallet = Wallet::where('user_id', $this->loginId)->first();
        $wallet_balance = $wallet->balance;
        $balance = 0;

        if ($wallet_balance  < $ServiceFee) {
            return response()->json([
                "message" => "Error",
                "errors" => array("Wallet Error" => "Sorry Wallet Not Sufficient for Transaction !")
            ], 422);
        } else {
            $balance = $wallet->balance - $ServiceFee;

            $affected = Wallet::where('user_id', $this->loginId)
                ->update(['balance' => $balance]);

            $serviceDesc = 'Wallet debitted with a service fee of ₦' . number_format($ServiceFee, 2);

            $this->transactionService->createTransaction($this->loginId, $ServiceFee, 'Premium NIN Slip', $serviceDesc,  'Wallet', 'Approved');

            //Generate PDF
            $repObj = new NIN_PDF_Repository();
            $response = $repObj->premiumPDF($nin_no);
            return  $response;
        }
    }

    public function premiumBVN($bvnno)
    {

        //BVN Services Fee
        $ServiceFee = 0;
        $ServiceFee = Service::where('service_code', '103')->first();
        $ServiceFee = $ServiceFee->amount;

        //Check if wallet is funded
        $wallet = Wallet::where('user_id', $this->loginId)->first();
        $wallet_balance = $wallet->balance;
        $balance = 0;

        if ($wallet_balance  < $ServiceFee) {
            return response()->json([
                "message" => "Error",
                "errors" => array("Wallet Error" => "Sorry Wallet Not Sufficient for Transaction !")
            ], 422);
        } else {
            $balance = $wallet->balance - $ServiceFee;



            $affected = Wallet::where('user_id', $this->loginId)
                ->update(['balance' => $balance]);


            $serviceDesc = 'Wallet debitted with a service fee of ₦' . number_format($ServiceFee, 2);

            $this->transactionService->createTransaction($this->loginId, $ServiceFee, 'Premium BVN Slip', $serviceDesc,  'Wallet', 'Approved');

            if (Verification::where('idno', $bvnno)->exists()) {

                $veridiedRecord = Verification::where('idno', $bvnno)
                    ->latest()
                    ->first();

                $data = $veridiedRecord;
                $view = view('verification.PremiumBVN', compact('veridiedRecord'))->render();

                return response()->json(['view' => $view]);
            } else {

                return response()->json([
                    "message" => "Error",
                    "errors" => array("Not Found" => "Verification record not found !")
                ], 422);
            }
        }
    }

    public function standardBVN($bvnno)
    {

        $ServiceFee = 0;
        $ServiceFee = Service::where('service_code', '102')->first();
        $ServiceFee = $ServiceFee->amount;

        $wallet = Wallet::where('user_id', $this->loginId)->first();
        $wallet_balance = $wallet->balance;
        $balance = 0;

        if ($wallet_balance  < $ServiceFee) {
            return response()->json([
                "message" => "Error",
                "errors" => array("Wallet Error" => "Sorry Wallet Not Sufficient for Transaction !")
            ], 422);
        } else {
            $balance = $wallet->balance - $ServiceFee;

            $affected = Wallet::where('user_id', $this->loginId)
                ->update(['balance' => $balance]);

            $serviceDesc = 'Wallet debitted with a service fee of ₦' . number_format($ServiceFee, 2);

            $this->transactionService->createTransaction($this->loginId, $ServiceFee, 'Standard BVN Slip', $serviceDesc,  'Wallet', 'Approved');

            if (Verification::where('idno', $bvnno)->exists()) {

                $veridiedRecord = Verification::where('idno', $bvnno)
                    ->latest()
                    ->first();

                $data = $veridiedRecord;
                $view = view('verification.freeBVN', compact('veridiedRecord'))->render();

                return response()->json(['view' => $view]);
            } else {

                return response()->json([
                    "message" => "Error",
                    "errors" => array("Not Found" => "Verification record not found !")
                ], 422);
            }
        }
    }

    public function plasticBVN($bvnno)
    {
        //Services Fee
        $ServiceFee = 0;
        $ServiceFee = Service::where('service_code', '109')->first();
        $ServiceFee = $ServiceFee->amount;

        //Check if wallet is funded
        $wallet = Wallet::where('user_id', $this->loginId)->first();
        $wallet_balance = $wallet->balance;
        $balance = 0;

        if ($wallet_balance  < $ServiceFee) {
            return response()->json([
                "message" => "Error",
                "errors" => array("Wallet Error" => "Sorry Wallet Not Sufficient for Transaction !")
            ], 422);
        } else {
            $balance = $wallet->balance - $ServiceFee;

            $affected = Wallet::where('user_id', $this->loginId)
                ->update(['balance' => $balance]);

            $serviceDesc = 'Wallet debitted with a service fee of ₦' . number_format($ServiceFee, 2);

            $this->transactionService->createTransaction($this->loginId, $ServiceFee, 'Plastic ID Card', $serviceDesc,  'Wallet', 'Approved');

            //Generate PDF
            $repObj = new BVN_PDF_Repository();
            $response = $repObj->plasticPDF($bvnno);
            return  $response;
        }
    }

    public function basicSlip($nin_no)
    {
        //NIN Services Fee
        $ServiceFee = 0;
        $ServiceFee = Service::where('service_code', '116')->first();
        $ServiceFee = $ServiceFee->amount;

        //Check if wallet is funded
        $wallet = Wallet::where('user_id', $this->loginId)->first();
        $wallet_balance = $wallet->balance;
        $balance = 0;

        if ($wallet_balance  < $ServiceFee) {
            return response()->json([
                "message" => "Error",
                "errors" => array("Wallet Error" => "Sorry Wallet Not Sufficient for Transaction !")
            ], 422);
        } else {
            $balance = $wallet->balance - $ServiceFee;

            $affected = Wallet::where('user_id', $this->loginId)
                ->update(['balance' => $balance]);

            $serviceDesc = 'Wallet debitted with a service fee of ₦' . number_format($ServiceFee, 2);

            $this->transactionService->createTransaction($this->loginId, $ServiceFee, 'Basic NIN Slip', $serviceDesc,  'Wallet', 'Approved');

            //Generate PDF
            $repObj = new NIN_PDF_Repository();
            $response = $repObj->basicPDF($nin_no);
            return  $response;
        }
    }

}
