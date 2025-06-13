<?php

namespace App\Http\Repositories;

use Exception;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VirtualAccountRepository
{

    protected string $secretKey;
    protected string $publicKey;
    protected string $baseUrl;
    protected array $banks;

    public function __construct()
    {
        $this->secretKey = config('billstack.credentials.secretkey');
        $this->publicKey = config('billstack.credentials.publickey');
        $this->baseUrl = config('billstack.credentials.baseurl');
        $this->banks     = config('billstack.credentials.banks');
    }

    public function createVirtualAccount($loginUserId)
    {
        $user = User::where('id', $loginUserId)
                    ->where('vwallet_is_created', 0)
                    ->first();

        if (! $user) {
            return false;
        }

            $nameParts = explode(' ', $user->name);
            $firstName = array_shift($nameParts);
            $lastName = implode(' ', $nameParts);

            $successCount = 0;

            foreach ($this->banks as $bankCode) {
                $refno = 'R-' . strtoupper(uniqid());

                $data = [
                    'reference' => $refno,
                    'firstName' => $firstName,
                    'lastName'  => $lastName,
                    'email'     => $user->email,
                    'phone'     => $user->phone_number,
                    'bank'      => $bankCode,
                ];

                $headers = [
                    "Authorization: Bearer {$this->secretKey}",
                    'Content-Type: application/json',
                ];

                $ch = curl_init();
                curl_setopt_array($ch, [
                    CURLOPT_URL => $this->baseUrl . '/generateVirtualAccount/',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POST => true,
                    CURLOPT_HTTPHEADER => $headers,
                    CURLOPT_POSTFIELDS => json_encode($data),
                ]);

                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                Log::info("[$bankCode] VA Response: $response");

                if (! $response || $httpCode >= 400) {
                    continue;
                }

                $result = json_decode($response, true);

                if (! ($result['status'] ?? false)) {
                    continue;
                }

                $accountReference = $result['data']['reference'];
                $accountData = $result['data']['account'];

                foreach ($accountData as $acc) {
                    DB::table('virtual_accounts')->insert([
                        'user_id'          => $user->id,
                        'accountReference' => $accountReference,
                        'accountNo'        => $acc['account_number'],
                        'accountName'      =>  'Billstack - '.  $firstName.' '.$lastName,
                        'bankName'         => $acc['bank_name'],
                        'status'           => '1',
                        'created_at'       => now(),
                        'updated_at'       => now(),
                    ]);
                    $successCount++;
                }
            }

            if ($successCount > 0) {
                User::where('id', $loginUserId)->update(['vwallet_is_created' => 1]);
                return true;
            } else {
                return response()->json(['error' => 'Failed to create any virtual account.'], 400);
            }
        }


        //For monify
    // public function createVirtualAccount($loginUserId)
    // {
    //     // $accessToken =  $this->getAccessToken();

    //     $exist = User::where('id', $loginUserId)
    //         ->where('vwallet_is_created', 0)
    //         ->exists();
    //     if ($exist) {

    //         $userDetails = User::where('id', $loginUserId)->first();

    //         $refno = md5(uniqid($userDetails->email));

    //         $nameParts = explode(' ', $userDetails->name);

    //         $firstName = $nameParts[0];
    //         $otherName = isset($nameParts[1]) ? $nameParts[1] : '';

    //         try {

    //             $data = [
    //                 "reference"     => $refno,
    //                 "firstName"          => $firstName,
    //                 "lastName"         => $otherName,
    //                 "email"        => $userDetails->email,
    //                 "phone"                  => $userDetails->phone_number,
    //                 "bank" => false,
    //             ];

    //             Log::info($data);

    //             $url = $this->baseUrl . '/generateVirtualAccount';

    //             $headers = [
    //                 "Authorization: Bearer  $this->secretKey",
    //                 'Content-Type: application/json',
    //             ];

    //             // Initialize cURL
    //             $ch = curl_init();

    //             // Set cURL options
    //             curl_setopt($ch, CURLOPT_URL, $url);
    //             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //             curl_setopt($ch, CURLOPT_POST, true);
    //             curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //             curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    //             // Execute request
    //             $response = curl_exec($ch);

    //             Log::info($response);
    //             // Check for cURL errors
    //             if (curl_errno($ch)) {
    //                 throw new \Exception('cURL Error: ' . curl_error($ch));
    //             }

    //             // Close cURL session
    //             curl_close($ch);

    //             // Decode the JSON response to an associative array
    //             $retrieveData = json_decode($response, true);

    //             // Proceed only if the request was successful
    //             if (! $retrieveData['requestSuccessful']) {
    //                 throw new Exception('Request was not successful.');
    //             }

    //             $responseBody = $retrieveData['responseBody'];
    //             $account_name = 'MFY/Champion technology-' . $responseBody['accountName'];
    //             $accountReference = $responseBody['accountReference'];
    //             $accounts = $responseBody['accounts'];

    //             $insertData = [];

    //             // Iterate through accounts and prepare data for insertion
    //             foreach ($accounts as $account) {
    //                 if (in_array($account['bankCode'], [$bankCode1, $bankCode2, $bankCode3])) {
    //                     $insertData[] = [
    //                         'user_id' => $loginUserId,
    //                         'accountReference' => $accountReference,
    //                         'accountNo' => $account['accountNumber'],
    //                         'accountName' => $account_name,
    //                         'bankName' => $account['bankName'],
    //                         'status' => '1',
    //                         'created_at' => now(),
    //                         'updated_at' => now(),
    //                     ];
    //                 }
    //             }

    //             // Perform batch insert if there is data to insert
    //             if (! empty($insertData)) {
    //                 DB::table('virtual_accounts')->insert($insertData);
    //             }

    //             // Update user to indicate virtual account creation
    //             User::where('id', $loginUserId)->update(['vwallet_is_created' => 1]);
    //             return true;
    //         } catch (\Exception $e) {
    //             Log::error('Error creating virtual account for user ' . $loginUserId . ': ' . $e->getMessage());

    //             return response()->json(['error' => 'Failed to create virtual account.'], 500);
    //         }
    //     }
    // }

    //for monnify
    // public function getAccessToken()
    // {

    //     try {

    //         $AccessKey = $this->apiKey . ':' .  $this->secret;
    //         $ApiKey = base64_encode($AccessKey);

    //         $url =  $this->baseUrl . '/v1/auth/login/';

    //         $headers = [
    //             'Accept: application/json, text/plain, */*',
    //             'Content-Type: application/json',
    //             "Authorization: Basic {$ApiKey}",
    //         ];

    //         // Initialize cURL
    //         $ch = curl_init();

    //         // Set cURL options
    //         curl_setopt($ch, CURLOPT_URL, $url);
    //         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //         curl_setopt($ch, CURLOPT_POST, true);
    //         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    //         // Execute request
    //         $response = curl_exec($ch);

    //         // Check for cURL errors
    //         if (curl_errno($ch)) {
    //             throw new \Exception('cURL Error: ' . curl_error($ch));
    //         }

    //         // Close cURL session
    //         curl_close($ch);


    //         $response = json_decode($response, true);
    //         return $response['responseBody']['accessToken'];
    //     } catch (\Exception $e) {
    //         Log::error('Error Authentication Monnify ' . auth()->user()->id . ': ' . $e->getMessage());
    //         return redirect()->back()->with('error', 'An error occurred while making the User BVN Verification');
    //     }
    // }
}
