<?php

namespace Jiannius\Atom\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class RecaptchaController extends Controller
{
    public function __invoke()
    {
        $token = request()->token;

        if (!$token || !app()->environment('production')) return response()->json(true);

        $sk = env('RECAPTCHA_SECRET_KEY');
        $api = env('RECAPTCHA_API_ENDPOINT');
        $min = env('RECAPTCHA_MIN_SCORE');

        $res = Http::asForm()->post(
            empty($api) ? 'https://www.google.com/recaptcha/api/siteverify' : $api,
            [
                'secret' => $sk,
                'response' => $token,
                'remoteip' => request()->ip(),
            ],
        )->throw()->json();

        if (
            !get($res, 'success')
            || get($res, 'score') < (empty($min) ? 0.5 : $min)
        ) {
            return response()->json(false);
        };

        return response()->json(true);
    }
}
