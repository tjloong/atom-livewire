<?php

namespace Jiannius\Atom\Http\Controllers;

use App\Http\Controllers\Controller;

class PdfController extends Controller
{
    /**
     * Index
     */
    public function index()
    {
        if (!request()->query('model') || !request()->query('find')) return abort(404);

        $model = model(request()->query('model'))->find(request()->query('find'));
        $pdf = $model->pdf(request()->all());
        $instance = data_get($pdf, 'instance');
        $filename = str()->finish(data_get($pdf, 'filename'), '.pdf');

        return (bool)request()->query('stream') === true
            ? $instance->stream($filename)
            : $instance->download($filename);
    }
}