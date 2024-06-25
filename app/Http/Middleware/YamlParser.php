<?php

namespace App\Http\Middleware;

use App\Models\HusmusenError;
use App\Models\HusmusenUser;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Closure;
use Symfony\Component\Yaml\Yaml;


class YamlParser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->header('Content-Type') === 'application/yaml') {
            $requestYamlData = Yaml::parse($request->getContent());

            foreach ($requestYamlData as $key => $value) {
                request()->instance()->request->set($key, $value);
            }
        }

        return $next($request);
    }
}
