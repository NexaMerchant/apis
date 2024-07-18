<?php
 namespace NexaMerchant\Apis\Http\Middleware;
 
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
 
class AssignRequestId
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $requestId = (string) Str::uuid();
 
        Log::withContext([
            'request-id' => $requestId
        ]);

        if(config("Apis.enable_input_log")) {
            Log::info('Request Input', [
                'request_id' => $requestId,
                'method' => $request->getMethod(),
                'url' => $request->getUri(),
                'input' => $request->all()
            ]);
        }
 
        $response = $next($request);


 
        $response->headers->set('Request-Id', $requestId);

        if(config("Apis.enable_output_log")) {
            Log::info('Response', [
                'request_id' => $requestId,
                'status' => $response->status(),
                'output' => $response->getContent()
            ]);
        
        }
 
        return $response;
    }
}