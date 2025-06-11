<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Service;
use App\Models\Wallet;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EnrollmentController extends Controller
{

    protected $transactionService;
    protected $loginId;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
        $this->loginId = auth()->user()->id;
    }

    public function bvnEnrollment()
    {
        $serviceCodes = ['110'];
        $services = Service::whereIn('service_code', $serviceCodes)
            ->get()
            ->keyBy('service_code');

        // Extract specific service fees
        $ServiceFee = $services->get('110') ?? 0.00;


        $enrollments = Enrollment::where('user_id',  $this->loginId)
            ->orderBy('id', 'desc')
            ->paginate(5);


        return view('enrollments.bvn-enrollment', compact('ServiceFee',  'enrollments'));
    }
    public function enrollBVN(Request $request)
    {

        $data = $request->validate([

            'phone' => 'required|numeric|digits:11|unique:bvn_enrollments,phone_number',
            'username' => 'nullable|string|max:255',
            'fullname' => 'required|string',
            'city'  => 'required|string',
            'state'  => 'required|string',
            'lga'  => 'required|string',
            'address'  => 'required|string',
            'email' => 'required|email|unique:bvn_enrollments,email',
            'account_name' => 'required|string',
            'account_number' => 'required|numeric|digits:10',
            'bank_name' => 'required|string',
            'bvn' => 'required|digits:11',
        ]);

        //NIN Services Fee
        $ServiceFee = 0;

        $ServiceFee = Service::where('service_code', '110')
            ->where('status', 'enabled')
            ->first();

        if (!$ServiceFee)
            return redirect()->route('user.bvn-enrollment')
                ->with('error', 'Service Error: Sorry Action not Allowed !');

        $ServiceFee = $ServiceFee->amount;

        $loginUserId = auth()->user()->id;

        //Check if wallet is funded
        $wallet = Wallet::where('user_id', $loginUserId)->first();
        $wallet_balance = $wallet->balance;
        $balance = 0;

        if ($wallet_balance < $ServiceFee) {
            return redirect()->route('user.bvn-enrollment')
                ->with('error', 'Wallet Error: Sorry Wallet Not Sufficient for Transaction !');
        } else {
            $responseurl = env('RESPONSE_URL');
            $data = [
                'fullname' => $request->fullname,
                'state' => $request->state,
                'lga' => $request->lga,
                'address' => $request->address,
                'city' => $request->city,
                'bvn' => $request->bvn,
                'account_number' => $request->account_number,
                'account_name' => $request->account_name,
                'bank_name' => $request->bank_name,
                'email' => $request->email,
                'phone_number' => $request->phone,
                'username' => $request->username,
                'url' => $responseurl
            ];

            try {

                $url = env('BASE_URL_VERIFY_USER') . 'api/v1/enrollement-bvn';
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

                if (isset($response['respCode']) && $response['respCode'] == '000') {

                    $data = $response['data'];

                    $balance = $wallet->balance - $ServiceFee;

                    Wallet::where('user_id', $loginUserId)
                        ->update(['balance' => $balance]);

                    //save the data
                    $this->saveEnrollmentRecord($data);

                    $serviceDesc = 'Wallet debitted with a service fee of ₦' . number_format($ServiceFee, 2);

                    $this->transactionService->createTransaction($loginUserId, $ServiceFee, 'BVN Enrollment', $serviceDesc,  'Wallet', 'Approved');

                    return redirect()->route('user.bvn-enrollment')
                        ->with('success', 'Enrollment record created successfully, Please note that this request will be processed in the next 5 Working days.');
                } else {
                    return redirect()->route('user.bvn-enrollment')
                        ->with('error', 'Failed: ' . $response);
                }
            } catch (\Exception $e) {
                return redirect()->route('user.bvn-enrollment')
                    ->with('error', 'An error occurred while making the API request');
            }
        }
    }

    function saveEnrollmentRecord($data)
    {
        try {
            // Create a new enrollment record
            $enrollment = new Enrollment();
            $enrollment->user_id = auth()->user()->id;
            $enrollment->refno = $data['refno'];
            $enrollment->fullname = $data['fullname'];
            $enrollment->state = $data['state'];
            $enrollment->lga = $data['lga'];
            $enrollment->address = $data['address'];
            $enrollment->city = $data['city'];
            $enrollment->bvn = $data['bvn'];
            $enrollment->account_number = $data['account_number'];
            $enrollment->account_name = $data['account_name'];
            $enrollment->bank_name = $data['bank_name'];
            $enrollment->email = $data['email'];
            $enrollment->phone_number = $data['phone_number'];
            $enrollment->username = $data['username'];
            // Save the record
            $enrollment->save();
        } catch (\Exception $e) {
            Log::error('Failed to save enrollment record: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Failed to save enrollment record',
                'error' => $e->getMessage()
            ];
        }
    }
}
