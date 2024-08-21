<?php

namespace Jiannius\Atom\Http\Controllers;

use App\Http\Controllers\Controller;

class FileController extends Controller
{
    // invoke
    public function __invoke()
    {
        if (request()->name) {
            $ulid = request()->query('ulid') ?? request()->name;
            $file = model('file')->withoutGlobalScopes()->findUlidOrFail($ulid);

            if ($variant = collect(request()->query())->keys()->reject('ulid')->first()) {
                $file = $file->variants($variant);
            }

            abort_if(!$file->auth(), 403);

            return $file->response();
        }
        else {
            $id = request()->query('id');
            $page = request()->query('page');
            $filters = request()->query('filters');
    
            return $id
                ? model('file')->whereIn('id', (array) $id)->latest('id')->get()->toArray()
                : model('file')->filter($filters)->latest('id')->toPage($page ?? 1, 100)->items();
        }
    }

    // upload
    public function upload() : mixed
    {
        if ($url = request()->url) {
            return collect($url)
                ->map(fn($url) => model('file')->store($url))
                ->values()
                ->all();
        }
        else {
            $path = request()->path;
            $visibility = request()->visibility;
            $uploads = request()->file('files');

            return collect($uploads)
                ->map(fn($upload) => model('file')->store($upload, $path, $visibility))
                ->toArray();
        }
    }
}