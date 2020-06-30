<?php


namespace App\Http\Middleware\Beacon;


use Closure;
use Illuminate\Http\Request;

class NavigatorBeaconCheck
{
    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $params = $request->all();

        if(isset($params['append_headers'])) {

            $headers = $params['append_headers'];

            if(is_string($headers)) {

                $result = json_decode($headers, true);

                if(json_last_error() === JSON_ERROR_NONE)
                    $headers = $result;

                $request->headers->add($headers);
            }

            unset($params['append_headers']);
        }

        if(!empty($params))
            $request->json()->add($params);

        return $next($request);
    }
}
