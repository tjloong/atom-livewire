<?php

namespace Jiannius\Atom\Components\Form;

use Illuminate\View\Component;

class Picker extends Component
{
    public $options;
    public $prevpage;
    public $nextpage;
    public $multiple;
    public $selected;
    public $clearOnOpen;
    public $isCountries = false;

    public function __construct(
        $options = [],
        $multiple = false,
        $selected = null,
        $clearOnOpen = false
    ) {
        if (is_string($options) && in_array($options, ['country', 'countries'])) {
            $this->options = metadata()->countries()->map(fn($cn) => [
                'value' => $cn->iso_code,
                'label' => $cn->name,
                'flag' => $cn->flag,
            ]);

            $this->isCountries = true;
        }
        else if (is_string($options) && str($options)->is('state*')) {
            [$str, $cn] = explode(':', $options);
            $this->options = metadata()->states($cn)->map(fn($st) => [
                'value' => $st->name,
                'label' => $st->name,
            ]);
        }
        else if (data_get($options, 'first_page_url')) {
            $this->options = data_get($options, 'data');

            $lastpage = data_get($options, 'last_page');
            $thispage = data_get($options, 'current_page');
            $this->nextpage = $lastpage > $thispage ? $thispage + 1 : null;
            $this->prevpage = $thispage === 1 ? null : $thispage - 1;
        }
        else $this->options = $options;

        $this->multiple = $multiple;
        $this->clearOnOpen = $clearOnOpen;
        
        $this->selected = $selected
            ? collect($this->options)->filter(fn($opt) => in_array(data_get($opt, 'value'), (array)$selected))
            : collect($this->options)->where('selected', true);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.form.picker');
    }
}