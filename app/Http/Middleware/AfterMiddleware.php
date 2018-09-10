<?php

namespace App\Http\Middleware;

use App\Models\SystemLog;
use Closure;
use Mockery\Exception;

/**
 * Class AfterMiddleware
 * @package App\Http\Middleware
 */
class AfterMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
//        \DB::enableQueryLog();
        $response = $next($request);

//        $queries = \DB::getQueryLog();
//        $url = $request->path();
//        $method = $request->getMethod();
//        $headers = getallheaders();
//        $params = $request->all();
//        if (!empty($request->route())) {
//            $action = $request->route()->getAction();
//            $uri = isset($action['as']) ? $action['as'] : $url;
//        } else {
//            $uri = $request->getUri();
//        }
//
//        \Log::debug('-------------------------------------------------------');
//        $contentLog = 'Request URL: [' . $method . '] ' . $url . PHP_EOL;
//        $contentLog .= 'Action URI: ' . $uri . PHP_EOL;
//        $contentLog .= '------------------------------------------------------------' . PHP_EOL;
//        $contentLog .= 'Headers:' . PHP_EOL;
//        $contentLog .= json_encode($headers, JSON_PRETTY_PRINT) . PHP_EOL;
//        $contentLog .= 'Params:' . PHP_EOL;
//        $contentLog .= json_encode($params, JSON_PRETTY_PRINT) . PHP_EOL;
//        $contentLog .= '----------- ------------------------------------------------' . PHP_EOL;
//        $contentLog .= 'Stack Trace:' . PHP_EOL;
//        $contentLog .= json_encode($queries, JSON_PRETTY_PRINT) . PHP_EOL;
//        $contentLog .= '------------------------------------------------------------' . PHP_EOL;
//        $contentLog .= 'END OF LOG' . PHP_EOL;
//        $contentLog .= '------------------------------------------------------------' . PHP_EOL . PHP_EOL;
//        \Log::debug($contentLog);

        return $response;
    }
}
