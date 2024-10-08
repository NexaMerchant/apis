<?php

namespace NexaMerchant\Apis\Http\Controllers\Api\V2;

use NexaMerchant\Apis\Http\Controllers\Api\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

class V2Controller extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
