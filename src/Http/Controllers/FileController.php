<?php

namespace Jiannius\Atom\Http\Controllers;

use App\Http\Controllers\Controller;

class FileController extends Controller
{
    /**
     * Index
     */
    public function index($id)
    {
        $file = model('file')->findOrFail($id);

        if ($this->verify($file)) {
            $path = $file->data->path ?? null;
            return response()->file(storage_path('app/'.$path));
        }
        else abort(404);
    }

    /**
     * Download
     */
    public function download($id)
    {
        $file = model('file')->findOrFail($id);

        if ($this->verify($file)) {
            $path = $file->data->path ?? null;
            return response()->download(storage_path('app/'.$path));
        }
        else abort(404);
    }

    /**
     * Verify
     */
    public function verify($file)
    {
        return auth()->user() && (
            auth()->user()->id === $file->created_by
            || auth()->user()->account->type === 'root'
        );
    }
}