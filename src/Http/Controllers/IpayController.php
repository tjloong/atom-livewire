<?php

namespace Jiannius\Atom\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Blade;

class IpayController extends Controller
{
    // checkout
    public function checkout() : mixed
    {
        $url = request()->url;
        $body = request()->body;
        $form = '';

        foreach (array_keys($body) as $key) {
            $form .= '<input name="'.$key.'" value="'.data_get($body, $key).'">';
        }

        $response = <<<EOL
        <form name="ipay_checkout" method="POST" action="$url" style="display: none;">$form</form>
        <div>Redirecting to payment gateway...</div>
        <script>window.onload = function() { document.forms['ipay_checkout'].submit() }</script>
        EOL;

        return Blade::render($response);
    }

    // redirect
    public function redirect()
    {
        if ($job = app('ipay')->getJobHandler()) {
            $status = request()->input('Status');
            $callback = $status === '1' ? 'success' : 'failed';
            return ($job)::dispatchSync($callback, request()->all(), 'ipay');
        }
    }

    // webhook
    public function webhook()
    {
        if ($job = app('ipay')->getJobHandler()) {
            $status = request()->input('Status');
            $callback = $status === '1' ? 'webhookSuccess' : 'webhookFailed';
            ($job)::dispatchSync($callback, request()->all(), 'ipay');
        }

        return 'RECEIVEOK';
    }
}