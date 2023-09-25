<?php

namespace Jiannius\Atom\Components\Form\Select;

use Illuminate\View\Component;

class Index extends Component
{
    public $parsedOptions;

    /**
     * Constructor
     */
    public function __construct(
        $options = []
    ) {
        $this->parsedOptions = collect($options)->map(function($opt) {
            if (is_string($opt)) return ['value' => $opt, 'label' => $opt];
            else {
                $value = data_get($opt, 'value') ?? data_get($opt, 'id') ?? data_get($opt, 'code');
                $label = data_get($opt, 'label') ?? data_get($opt, 'name') ?? data_get($opt, 'title') ?? data_get($opt, 'value') ?? data_get($opt, 'id') ?? data_get($opt, 'code');
                $small = data_get($opt, 'small') ?? data_get($opt, 'description') ?? data_get($opt, 'caption');
                $remark = data_get($opt, 'remark');
    
                return [
                    'value' => $value,
                    'label' => $label,
                    'small' => $small,
                    'remark' => $remark,
                    'status' => data_get($opt, 'status'),
                    'is_group' => data_get($opt, 'is_group'),
                    'avatar' => data_get($opt, 'avatar'),
                    'image' => data_get($opt, 'image'),
                    'flag' => data_get($opt, 'flag'),
                    'color' => [
                        'green' => 'bg-green-100 text-green-600 hover:bg-green-300 text-green-800',
                        'blue' => 'bg-blue-100 text-blue-600 hover:bg-blue-300 text-blue-800',
                        'orange' => 'bg-orange-100 text-orange-600 hover:bg-orange-300 text-orange-800',
                    ][data_get($opt, 'color')] ?? 'hover:bg-slate-100',
                ];
            }
        });
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.form.select.index');
    }
}