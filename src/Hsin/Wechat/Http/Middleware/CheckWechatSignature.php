<?php

namespace Hsin\Wechat\Http\Middleware;

use \Closure;

class CheckWechatSignature
{
    /**
     * Run the request filter.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @throws \Exception
     */
    public function handle($request, Closure $next)
    {
        if (!$request->has('signature') || !$request->has('timestamp') || !$request->has('nonce')) {
            throw new \Exception(trans('wechat.illegal_access'));
        }

        $signature = $request->input('signature');
        $timestamp = $request->input('timestamp');
        $nonce = $request->input('nonce');

        $app = wechat($request->route('one'));

        if (! $app->checkSignature($signature, $timestamp, $nonce)) {
            throw new \Exception(trans('wechat.illegal_access'));
        }

        return $next($request);
    }
}