<?php

return [
    /**
     * If this is false, don't try and use staging configs if the current environment is anything but production
     */
    'useStaging'    => true,

    'production'    => [
        'client_id'     => '',
        'client_secret' => '',
        'access_token'  => '',
        'account_id'    => ''
    ],
    'staging'       => [
        'client_id'     => '',
        'client_secret' => '',
        'access_token'  => '',
        'account_id'    => ''
    ]
];
