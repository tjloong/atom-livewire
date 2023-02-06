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
        $source = $this->getSource($file);

        if ($url = data_get($source, 'url')) return redirect()->to($url);
        else if ($path = data_get($source, 'path')) return response()->file($path);
        else abort(404);
    }

    /**
     * Upload
     */
    public function upload()
    {
        $upload = request()->file('upload');
        $location = request()->input('location', 'uploads');
        $visibility = request()->input('visibility', 'public');

        $file = model('file')->store($upload, $location, $visibility);

        return response()->json($file);
    }

    /**
     * Download
     */
    public function download($id)
    {
        $file = model('file')->findOrFail($id);
        $source = $this->getSource($file);

        if ($url = data_get($source, 'url')) return response()->download($url);
        else if ($path = data_get($source, 'path')) return response()->download($path);
        else abort(404);
    }

    /**
     * Get source
     */
    public function getSource($file)
    {
        if (!$this->verify($file)) return;

        $path = data_get($file->data, 'path');
        $provider = data_get($file->data, 'provider', 'local');

        if ($provider === 'local') return ['path' => storage_path('app/'.$path)];
        else if ($disk = model('file')->getStorageDisk($provider)) return ['url' => $disk->temporaryUrl($path, now()->addDay(1))];
    }

    /**
     * Verify
     */
    public function verify($file)
    {
        return auth()->user() && (
            auth()->user()->id === $file->created_by
            || auth()->user()->is_root
        );
    }
}