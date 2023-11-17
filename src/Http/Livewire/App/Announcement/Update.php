<?php

namespace Jiannius\Atom\Http\Livewire\App\Announcement;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;

class Update extends Component
{
    use WithForm;

    public $inputs;
    public $announcement;

    protected $listeners = [
        'createAnnouncement' => 'create',
        'updateAnnouncement' => 'update',
    ];

    // validation
    protected function validation() : array
    {
        return [
            'announcement.name' => ['required' => 'Announcement name is required.'],
            'announcement.slug' => [
                'nullable',
                function ($attr, $value, $fail) {
                    if (model('announcement')->where('slug', $value)->where('id', '<>', $this->announcement->id)->count()) {
                        $fail('Slug is taken.');
                    }
                },
            ],
            'announcement.href' => ['nullable'],
            'announcement.content' => ['nullable'],
            'announcement.seo' => ['nullable'],
            'announcement.start_at' => ['nullable'],
            'announcement.end_at' => ['nullable'],
        ];
    }

    // create
    public function create() : void
    {
        $this->announcement = model('announcement')->fill(['start_at' => now()]);
        $this->open();
    }

    // update
    public function update($id = null) : void
    {
        if ($this->announcement = model('announcement')->find($id)) {
            $this->open();
        }
    }

    // open
    public function open() : void
    {
        if ($this->announcement) {
            $this->resetValidation();
    
            $this->fill([
                'inputs.seo' => [
                    'title' => data_get($this->announcement->seo, 'title'),
                    'description' => data_get($this->announcement->seo, 'description'),
                    'image' => data_get($this->announcement->seo, 'image'),
                ],
                'inputs.data' => [
                    'bg_color' => data_get($this->announcement->data, 'bg_color'),
                    'text_color' => data_get($this->announcement->data, 'text_color'),
                ],
            ]);
    
            $this->openDrawer('announcement-update');
        }
    }

    // close
    public function close() : void
    {
        $this->emit('setAnnouncementId');
        $this->closeDrawer('announcement-update');
    }

    // duplicate
    public function duplicate() : void
    {
        model('announcement')->create([
            'name' => $this->announcement->name.' Copy',
            'href' => $this->announcement->href,
            'content' => $this->announcement->content,
            'seo' => $this->announcement->seo,
        ]);

        $this->emit('announcementCreated');
        $this->close();
    }

    // delete
    public function delete() : void
    {
        $this->announcement->delete();
        $this->emit('announcementDeleted');
        $this->close();
    }

    // submit
    public function submit() : void
    {
        $this->validateForm();

        $this->announcement->fill([
            'seo' => data_get($this->inputs, 'seo'),
            'data' => data_get($this->inputs, 'data'),
        ])->save();

        if ($this->announcement->wasRecentlyCreated) $this->emit('announcementCreated');
        else $this->emit('announcementUpdated');

        $this->close();
    }
}