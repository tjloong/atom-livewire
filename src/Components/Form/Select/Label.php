<?php

namespace Jiannius\Atom\Components\Form\Select;

use Illuminate\View\Component;

class Label extends Component
{
    public $type;
    public $options;

    // constructor
    public function __construct(
        $type = null, 
        $parent = null,
        $options = null,
        $children = true,
    ) {
        $this->type = $type;
        $this->options = collect();

        $this->makeOptions(
            $options ?? model('label')
                ->readable()
                ->when($type, fn($q) => $q->where('type', $type))
                ->when($parent, 
                    fn($q) => $q->where('parent_id', $parent),
                    fn($q) => $q->whereNull('parent_id'),
                )
                ->oldest('seq')
                ->oldest('id')
                ->get(), 
            $children
        );
    }

    // make options
    public function makeOptions($labels, $children)
    {
        foreach ($labels as $label) {
            $this->options->push([
                'value' => $label->id,
                'label' => (
                    $label->parents->count()
                    ? $label->parents
                        ->map(fn($parent) => $parent->locale('name'))
                        ->map(fn($val) => str($val)->limit(15)->toString())
                        ->join(' / ').' / '
                    : ''
                ).$label->locale('name'),
            ]);

            if ($children && $label->children->count()) {
                $this->makeOptions($label->children, $children);
            }
        }
    }

    // render
    public function render()
    {
        return view('atom::components.form.select.label');
    }
}