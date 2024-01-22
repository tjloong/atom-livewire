<?php

namespace Jiannius\Atom\Http\Controllers;

use App\Http\Controllers\Controller;

class FileController extends Controller
{
    // get
    public function get($ulid, $size = null) : mixed
    {
        if (($file = model('file')->withoutGlobalScopes()->findUlidOrFail($ulid)) && $file->auth()) {
            if ($size) {
                // TODO get thumbnail for size
                // return thumbnail response
            }

            return $file->response();
        }

        abort(401);
    }

    // list
    public function list() : mixed
    {
        $id = request()->id;
        $page = request()->page;
        $filters = request()->filters;

        return $id
            ? model('file')->whereIn('id', (array) $id)->latest('id')->get()->toArray()
            : model('file')->filter($filters)->latest('id')->toPage($page ?? 1, 100)->items();
    }

    // url
    public function url() : mixed
    {
        $url = request()->url;

        return collect($url)
            ->map(fn($url) => model('file')->store($url))
            ->values()
            ->all();
    }

    // upload
    public function upload() : mixed
    {
        $path = request()->path;
        $visibility = request()->visibility;
        $uploads = request()->file('files');

        return collect($uploads)
            ->map(fn($upload) => model('file')->store($upload, $path, $visibility))
            ->toArray();
    }
}