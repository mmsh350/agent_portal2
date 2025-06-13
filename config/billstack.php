
<?php

return [
    'credentials' => [
        'secretkey' => env('SECRTE_KEY', ''),
        'publickey' => env('PUBLIC_KEY', ''),
        'baseurl' => env('BILL_BASE_URL', ''),
        'banks' => explode(',', env('BANK_NAMES')),
    ],
];
