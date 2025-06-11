<?php

session_start();
include 'include/init/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bvnbtn'])) {
    $userid = $_SESSION['userid'] ?? null;

    if ($userid) {
        $sql = $conn->prepare('SELECT UserBalance FROM balance WHERE userID = ?');
        $sql->bind_param('s', $userid);
        $sql->execute();

        $result = $sql->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $userBalance = $row['UserBalance'];

            if ((float) $userBalance >= 150.0) {
                $bvn = $_POST['bvn'];

                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_URL => 'https://api.checkid.ng/api/v1/identity/bvn',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => ['bvn' => $bvn, 'type' => 'lookup'],
                    CURLOPT_HTTPHEADER => [
                        'Authorization: Bearer live_EBY4jMLXFP13WZJucUZLQ/HhSB0nsQuIguLV2qCxDYcj2P8R',
                    ],
                ]);

                $response = curl_exec($curl);
                curl_close($curl);

                $data = json_decode($response, true);

                if (json_last_error() === JSON_ERROR_NONE && isset($data['message']) && $data['message'] === 'BVN verified successfully' && isset($data['status']) && $data['status'] === 200) {
                    $base64String = 'data:image/png;base64,'.$data['data']['entity']['image'];
                    $bvn = $data['data']['entity']['bvn'];
                    $first_name = $data['data']['entity']['first_name'];
                    $middle_name = $data['data']['entity']['middle_name'];
                    $last_name = $data['data']['entity']['last_name'];
                    $date_of_birth = $data['data']['entity']['date_of_birth'];
                    $phone_number = $data['data']['entity']['phone_number1'];
                    $gender = $data['data']['entity']['gender'];
                    $photo = $data['data']['entity']['image'];

                    $_SESSION['imge'] = $base64String;
                    $_SESSION['bvn'] = $bvn;
                    $_SESSION['first_name'] = $first_name;
                    $_SESSION['middle_name'] = $middle_name;
                    $_SESSION['last_name'] = $last_name;
                    $_SESSION['date_of_birth'] = $date_of_birth;
                    $_SESSION['gender'] = $gender;
                    $_SESSION['phone_number'] = $phone_number;
                    $_SESSION['photo'] = $photo;

                    $newbalance = (float) $userBalance - 150.0;
                    $_SESSION['balance'] = $newbalance;
                    $userid = $_SESSION['userid'];

                    $update = "UPDATE balance SET UserBalance = $newbalance WHERE userID = '$userid'";

                    if ($conn->query($update) === true) {
                        $userID = $_SESSION['userid'];
                        $service_type = 'BVN Slip';
                        $amount = '150';
                        $searching_query = 'By BVN';
                        $status = 'success';
                        $date = date('Y-m-d');

                        $stmt = $conn->prepare('INSERT INTO bvn_services (id, userID, service_type, amount,  `status`, `date`) VALUES (NULL, ?, ?, ?, ?, ?)');
                        $stmt->bind_param('sssss', $userID, $service_type, $amount, $status, $date);
                        $stmt->execute();

                        $api = $conn->prepare('INSERT INTO api_charges (id, amount) VALUES (NULL, ?)');
                        $amt = 50.0;
                        $api->bind_param('d', $amt);
                        $api->execute();

                        $_SESSION['alert1'] = "Record Found!, Remaining balance: $newbalance";
                        header('Location: bvndetails.php?id='.md5('jnbdfbndfjbndksmwoeif9rughbdfbnkjcx'));
                        exit();
                    } else {
                        $_SESSION['alert2'] = 'Error occurred while updating balance: '.$conn->error;
                        header('Location: bvn.php');
                        exit;
                    }
                } else {
                    $_SESSION['alert2'] = 'User Record Not Found or Invalid BVN';
                    header('Location: bvn.php');
                    exit;
                }
            } else {
                $_SESSION['alert2'] = 'Insufficient Balance to Perform this Task';
                header('Location: bvn.php');
                exit;
            }
        } else {
            $_SESSION['alert2'] = 'No Balance Record Found for the User';
            header('Location: bvn.php');
            exit;
        }
    } else {
        $_SESSION['alert2'] = 'User ID not found in session';
    }
} elseif (isset($_SESSION['bvn'])) {
    header('Location: bvndetails.php?id='.md5('jnbdfbndfjbndksmwoeif9rughbdfbnkjcx'));
    exit();
} else {
    $_SESSION['alert'] = 'Error Occurred. Contact Development Team!!!';
    header('Location: bvn.php');
    exit();
}
