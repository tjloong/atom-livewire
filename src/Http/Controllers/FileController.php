<?php

namespace Jiannius\Atom\Http\Controllers;

use App\Http\Controllers\Controller;

class FileController extends Controller
{
    public function get($ulid, $size = null) : mixed
    {
        if (($file = model('file')->findUlidOrFail($ulid)) && $file->auth()) {
            if ($size) {
                // TODO get thumbnail for size
                // return thumbnail response
            }

            return $file->response();
        }

        abort(401);
    }
}