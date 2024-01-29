<?php

namespace Jiannius\Atom\Http\Livewire\App\File;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;

class Update extends Component
{
    use WithForm;

    public $file;
    public $inputs;

    protected $listeners = [
        'updateFile' => 'open',
    ];

    // validation
    protected function validation(): array
    {
        return [
            'file.name' => ['required' => 'File name is required.'],
        ];
    }

    // open
    public function open($id) : void
    {
        if ($this->file = model('file')->find($id)) {
            $this->fill([
                'inputs.alt' => data_get($this->file->data, 'alt'),
                'inputs.description' => data_get($this->file->data, 'description'),
            ]);

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
        
        $this->file->fill([
            'data' => array_merge((array) $this->file->data, $this->inputs),
        ])->save();

        $this->emit('fileUpdated');
        $this->close();
    }
}