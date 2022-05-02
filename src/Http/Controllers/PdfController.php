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

        return (bool)request()->query('stream') === true
            ? $pdf->instance->stream($pdf->filename)
            : $pdf->instance->download($pdf->filename);
    }
}