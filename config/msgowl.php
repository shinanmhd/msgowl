<?php

// config for Hadhiya/MsgOwl
return [
    /*
     * Your MsgOwl Access Key.
     * Obtain this from https://msgowl.com/dashboard/settings/api
     */
    'api_key' => env('MSGOWL_API_KEY'),

    /*
     * The default Sender ID to be used for all outgoing SMS.
     * This can be overridden in individual calls.
     */
    'sender_id' => env('MSGOWL_SENDER_ID', 'HADHIYA'),

    // OTP Specific Credentials
    'otp_key' => env('MSGOWL_OTP_KEY'),
    'otp_secret' => env('MSGOWL_OTP_SECRET'),
];
