<?php
return [
    'api_site_key'                 => env('RECAPTCHA_SITE_KEY', ''),
    'api_secret_key'               => env('RECAPTCHA_SECRET_KEY', ''),
    'version'                      => 'v2',
    'curl_timeout'                 => 10,
    'skip_ip'                      => env('RECAPTCHA_SKIP_IP', []),
    'default_validation_route'     => 'biscolab-recaptcha/validate',
    'default_token_parameter_name' => 'token',
    'default_language'             => null,
    'default_form_id'              => 'biscolab-recaptcha-invisible-form',
    'explicit'                     => false,
    'api_domain'                   => 'www.recaptcha.net',
    'empty_message' => false,
    'error_message_key' => 'Captcha tidak valid!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!',
    'tag_attributes'               => [
        'theme'            => 'light',
        'size'             => 'normal',
        'tabindex'         => 2,
        "callback" => "callbackFunction",
        "expired-callback" => "expiredCallbackFunction",
        "error-callback" => "errorCallbackFunction",
    ]
];
