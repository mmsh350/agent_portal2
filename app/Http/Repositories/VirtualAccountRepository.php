<?php

namespace App\Http\Repositories;

use Exception;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VirtualAccountRepository
{

    protected string $secret;
    protected string $contractCode;
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->secret = config('monnify.credentials.monnifysecret');
        $this->contractCode = config('monnify.credentials.monnifycontract');
        $this->apiKey = config('monnify.credentials.monnifyapi');
        $this->baseUrl = config('monnify.credentials.monnify_base_url');
    }

    public function createVirtualAccount($loginUserId)
    {
        $accessToken =  $this->getAccessToken();

        $exist = User::where('id', $loginUserId)
            ->where('vwallet_is_created', 0)
            ->exists();
        if ($exist) {

            $userDetails = User::where('id', $loginUserId)->first();

            $refno = md5(uniqid($userDetails->email));

            $bankCode1 = env('BANKCODE1');
            $bankCode2 = env('BANKCODE2');
            $bankCode3 = env('BANKCODE3');


            try {

                $data = [
                    "accountReference"     => $refno,
                    "accountName"          => $userDetails->name,
                    "currencyCode"         => "NGN",
                    "contractCode"         => $this->contractCode,
                    "customerEmail"        => $userDetails->email,
                    "customerName"         => $userDetails->name,
                    "bvn"                  => '22705871738',
                    "getAllAvailableBanks" => false,
                    "preferredBanks"       => [$bankCode1, $bankCode2, $bankCode3],
                ];

                Log::info($data);

                $url = env('MONNIFY_BASE_URL') . '/v2/bank-transfer/reserved-accounts';

                $headers = [
                    "Authorization: Bearer $accessToken",
                    'Content-Type: application/json',
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

                Log::info($response);
                // Check for cURL errors
                if (curl_errno($ch)) {
                    throw new \Exception('cURL Error: ' . curl_error($ch));
                }

                // Close cURL session
                curl_close($ch);

                // Decode the JSON response to an associative array
                $retrieveData = json_decode($response, true);

                // Proceed only if the request was successful
                if (! $retrieveData['requestSuccessful']) {
                    throw new Exception('Request was not successful.');
                }

                $responseBody = $retrieveData['responseBody'];
                $account_name = 'MFY/Champion technology-' . $responseBody['accountName'];
                $accountReference = $responseBody['accountReference'];
                $accounts = $responseBody['accounts'];

                $insertData = [];

                // Iterate through accounts and prepare data for insertion
                foreach ($accounts as $account) {
                    if (in_array($account['bankCode'], [$bankCode1, $bankCode2, $bankCode3])) {
                        $insertData[] = [
                            'user_id' => $loginUserId,
                            'accountReference' => $accountReference,
                            'accountNo' => $account['accountNumber'],
                            'accountName' => $account_name,
                            'bankName' => $account['bankName'],
                            'status' => '1',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }

                // Perform batch insert if there is data to insert
                if (! empty($insertData)) {
                    DB::table('virtual_accounts')->insert($insertData);
                }

                // Update user to indicate virtual account creation
                User::where('id', $loginUserId)->update(['vwallet_is_created' => 1]);
                return true;
            } catch (\Exception $e) {
                Log::error('Error creating virtual account for user ' . $loginUserId . ': ' . $e->getMessage());

                return response()->json(['error' => 'Failed to create virtual account.'], 500);
            }
        }
    }

    public function getAccessToken()
    {

        try {

            $AccessKey = $this->apiKey . ':' .  $this->secret;
            $ApiKey = base64_encode($AccessKey);

            $url =  $this->baseUrl . '/v1/auth/login/';

            $headers = [
                'Accept: application/json, text/plain, */*',
                'Content-Type: application/json',
                "Authorization: Basic {$ApiKey}",
            ];

            // Initialize cURL
            $ch = curl_init();

            // Set cURL options
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            // Execute request
            $response = curl_exec($ch);

            // Check for cURL errors
            if (curl_errno($ch)) {
                throw new \Exception('cURL Error: ' . curl_error($ch));
            }

            // Close cURL session
            curl_close($ch);


            $response = json_decode($response, true);
            return $response['responseBody']['accessToken'];
        } catch (\Exception $e) {
            Log::error('Error Authentication Monnify ' . auth()->user()->id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while making the User BVN Verification');
        }
    }
}
