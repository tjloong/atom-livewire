<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\Label;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;

class Update extends Component
{
    use WithForm;
    use WithPopupNotify;

    public $label;
    public $inputs;

    protected $listeners = ['open'];

    // validation
    protected function validation(): array
    {
        return array_merge(
            [
                'label.type' => ['nullable'],
                'label.slug' => ['nullable'],
                'label.parent_id' => ['nullable'],
            ],

            $this->locales->mapWithKeys(fn($locale, $key) => [
                'inputs.name.'.$locale => ['required' => 'Label name ('.$locale.') is required.'],
            ])->toArray(),
        );
    }

    // get locales property
    public function getLocalesProperty(): mixed
    {
        return collect(config('atom.locales'));
    }

    // open
    public function open($data = null): void
    {
        $id = is_numeric($data) ? $data : data_get($data, 'id');

        $this->label = $id 
            ? model('label')->findOrFail($id)
            : model('label')->fill($data);

        $this->fill([
            'inputs.name' => (array) $this->label->name,
        ]);
    
        $this->dispatchBrowserEvent('label-update-open');
    }

    // close
    public function close(): void
    {
        $this->reset('label', 'inputs');
        $this->dispatchBrowserEvent('label-update-close');
    }

    // delete
    public function delete($id): void
    {
        $this->label->delete();
        $this->emit('labelSaved');
        $this->close();
    }

    // submit
    public function submit(): void
    {
        $this->validateForm();

        $this->label->fill([
            'name' => data_get($this->inputs, 'name'),
            'slug' => null,
        ])->save();

        $this->emit('labelSaved');
        $this->close();
    }
}