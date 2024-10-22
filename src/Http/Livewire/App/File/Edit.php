<?php

namespace Jiannius\Atom\Http\Livewire\App\File;

use Jiannius\Atom\Atom;
use Jiannius\Atom\Traits\Livewire\AtomComponent;
use Livewire\Component;

class Edit extends Component
{
    use AtomComponent;

    public $file;

    protected function validation(): array
    {
        return [
            'file.name' => ['required' => 'File name is required.'],
            'file.data' => ['nullable'],
            'file.data.alt' => ['nullable'],
            'file.data.description' => ['nullable'],
        ];
    }

    public function open($id) : void
    {
        $this->resetValidation();

        $this->file = model('file')->find($id);

        $this->file->fill([
            'data' => [
                'alt' => null,
                'description' => null,
                ...$this->file->data ?? [],
            ],
        ]);
    }

    public function close() : void
    {
        $this->commandTo('app.file.listing', 'refresh');
        Atom::modal('app.file.edit')->close();
    }

    public function delete() : void
    {
        $this->file->delete();
        $this->close();
    }

    public function submit() : void
    {
        $this->validate();
        $this->file->save();
        $this->close();
    }
}