<?php
return [
    'name' => 'Apis',
    'version' => '1.0.7',
    'versionNum' => '107',
    'enable_input_log' => env("APIS_ENABLE_INPUT_LOG", false), // Enable input log for all requests
    'enable_output_log' => env("APIS_ENABLE_OUTPUT_LOG", false), // Enable output log for all requests
];