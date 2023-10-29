<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\File;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;

class Update extends Component
{
    use WithForm;

    public $file;

    protected $listeners = [
        'updateFile' => 'open',
    ];

    // validation
    protected function validation(): array
    {
        return [
            'file.name' => ['required' => 'File name is required.'],
            'file.data.alt' => ['nullable'],
            'file.data.description' => ['nullable'],
        ];
    }

    // open
    public function open($id) : void
    {
        if ($this->file = model('file')->find($id)) {
            $this->openDrawer('file-update');
        }
    }

    // close
    public function close() : void
    {
        $this->closeDrawer('file-update');
    }

    // delete
    public function delete() : void
    {
        $this->file->delete();
        $this->emit('fileDeleted');
        $this->close();
    }

    // submit
    public function submit() : void
    {
        $this->validateForm();
        $this->file->save();
        $this->emit('fileUpdated');
        $this->close();
    }
}