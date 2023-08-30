<?php

namespace Jiannius\Atom\Http\Controllers;

use App\Http\Controllers\Controller;

class RevenueMonsterController extends Controller
{
    // redirect
    public function redirect()
    {
        return app('App\Jobs\RevenueMonsterProvision')::dispatchSync([
            'meta' => request()->query(),
        ]);
    }

    // webhook
    public function webhook()
    {
        return app('App\Jobs\RevenueMonsterProvision')::dispatchSync([
            'meta' => request()->all(),
            'webhook' => true,
        ]);
    }
}