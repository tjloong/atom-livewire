<?php

namespace Jiannius\Atom\Http\Controllers;

use App\Http\Controllers\Controller;

class ExportController extends Controller
{
    /**
     * Download
     */
    public function download()
    {
        $path = storage_path('export/' . request()->filename);
        return response()->download($path)->deleteFileAfterSend(true);
    }
}