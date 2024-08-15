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
    'version' => '1.1.1',
    /**
     * 
     * The version number of the package
     */
    'versionNum' => '111',
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

];