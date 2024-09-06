<?php
return [
    /**
     * 
     * The name of the package
     */
    'name' => 'Apis',
    /**
     * 
     * The version of the package
     */
    'version' => '2.0.0',
    /**
     * 
     * The version number of the package
     */
    'versionNum' => '200',
    /**
     * 
     * enable input log for all requests default is false
     * 
     */
    'enable_input_log' => env("APIS_ENABLE_INPUT_LOG", false), // Enable input log for all requests
    /**
     * 
     * enable output log for all requests default is false
     * 
     */
    'enable_output_log' => env("APIS_ENABLE_OUTPUT_LOG", false), // Enable output log for all requests

    'description' => 'Apis package is a package that contains all the apis for the application',

    'license' => 'MIT',

    'author' => 'Steve',

    'email' => 'nice.lizhi@gmail.com',

    'homepage' => 'https://nexa-merchant.com',

    'composer' => "nexa-merchant/apis",

    'keywords' => [
        'Apis',
        'API',
        'APIs',
        'APIs package',
        'APIs for the application',
    ],

    

];