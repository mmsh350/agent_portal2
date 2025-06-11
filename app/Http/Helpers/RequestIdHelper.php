<?php

namespace App\Http\Helpers;

class RequestIdHelper
{
    public static function generateRequestId()
    {
        // Set the timezone to Africa/Lagos
        date_default_timezone_set('Africa/Lagos');

        // Generate the current date and time in the format YYYYMMDDHHII
        $requestId = date('YmdHi');

        // Concatenate with a random alphanumeric string to ensure the ID is more than 12 characters
        // You can use any method to generate the random string. Here's one way using bin2hex(random_bytes())
        $randomString = bin2hex(random_bytes(6)); // Generates a 12-character random string

        // Combine the date/time string with the random alphanumeric string
        $requestId .= $randomString;

        return $requestId;
    }
}
