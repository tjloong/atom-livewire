<?php

namespace Jiannius\Atom\Livewire\File;

use Illuminate\Support\Arr;
use Jiannius\Atom\Atom;
use Jiannius\Atom\Traits\Livewire\AtomComponent;
use Livewire\Component;

class Edit extends Component
{
    use AtomComponent;

    public $file;
    public $canEditVisibility = true;

    protected function validation(): array
    {
        return [
            'file.name' => ['required' => 'File name is required.'],
            'file.data' => ['nullable'],
            'file.data.env' => ['nullable'],
            'file.data.alt' => ['nullable'],
            'file.data.description' => ['nullable'],
            'file.data.visibility' => ['nullable'],
        ];
    }

    public function open($data = []) : void
    {
        $id = Arr::pull($data, 'id');
        $this->canEditVisibility = Arr::pull($data, 'can_edit_visibility');

        $this->file = model('file')->find($id);
        $this->file->fill([
            'data' => [
                'alt' => null,
                'description' => null,
                'visibility' => $this->file->getDefaultVisibility(),
                ...$this->file->data ?? [],
            ],
        ]);

        $this->refresh();
    }

    public function delete() : void
    {
        $this->file->delete();
        $this->emit('file-deleted', $this->file->id);
        $this->close();
    }

    public function submit() : void
    {
        $this->validate();
        $this->file->save();
        $this->emit('file-updated', $this->file->id);
        $this->close();
    }

    public function close() : void
    {
        Atom::modal('atom.file.edit')->close();
    }
}