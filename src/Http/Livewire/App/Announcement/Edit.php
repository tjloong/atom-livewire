<?php

namespace Jiannius\Atom\Http\Livewire\App\Announcement;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;

class Edit extends Component
{
    use WithForm;

    public $inputs;
    public $announcement;

    protected $listeners = [
        'editAnnouncement' => 'open',
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
            'announcement.bg_color' => ['nullable'],
            'announcement.text_color' => ['nullable'],
            'announcement.seo' => ['nullable'],
            'announcement.start_at' => ['nullable'],
            'announcement.end_at' => ['nullable'],
        ];
    }

    // open
    public function open($data = []) : void
    {
        $id = get($data, 'id');

        if (
            $this->announcement = $id
            ? model('announcement')->find($id)
            : model('announcement')->fill(['start_at' => now(), ...$data])
        ) {
            $this->resetValidation();
    
            $this->fill(['inputs.seo' => [
                'title' => data_get($this->announcement->seo, 'title'),
                'description' => data_get($this->announcement->seo, 'description'),
                'image' => data_get($this->announcement->seo, 'image'),
            ]]);
    
            $this->overlay();
        }
    }

    // duplicate
    public function duplicate() : void
    {
        model('announcement')->create([
            'name' => $this->announcement->name.' Copy',
            'href' => $this->announcement->href,
            'content' => $this->announcement->content,
            'bg_color' => $this->announcement->bg_color,
            'text_color' => $this->announcement->text_color,
            'seo' => $this->announcement->seo,
        ]);

        $this->overlay(false);
    }

    // delete
    public function delete() : void
    {
        $this->announcement->delete();
        $this->overlay(false);
    }

    // submit
    public function submit() : void
    {
        $this->validateForm();
        $this->announcement->fill(['seo' => data_get($this->inputs, 'seo')])->save();
        $this->overlay(false);
    }
}