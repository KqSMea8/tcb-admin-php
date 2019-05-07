<?php
namespace Tcb\PromiseCallback;

use GuzzleHttp\Promise\Promise;
function createPromiseCallback()
{
    $callback = array('promise' => null, 'callFunc' => null);
    $promise = new Promise(function () use (&$promise, &$callback) {
        $callback['callFunc'] = function ($err, $data) use (&$promise) {
            if (isset($err)) {
                $promise->reject($err);
            } else {
                $promise->resolve($data);
            }
        };

    });
    $callback['promise'] = $promise;
    return $callback;
}
