<?php

namespace Jiannius\Atom\Http\Livewire\App\Announcement;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;

class Edit extends Component
{
    use WithForm;

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
            'announcement.seo.title' => ['nullable'],
            'announcement.seo.description' => ['nullable'],
            'announcement.seo.image' => ['nullable'],
            'announcement.seo.canonical' => ['nullable'],
            'announcement.start_at' => ['nullable'],
            'announcement.end_at' => ['nullable'],
        ];
    }

    // open
    public function open($data = []) : void
    {
        $this->resetValidation();

        if ($this->announcement = model('announcement')->firstOrNew(
            ['id' => get($data, 'id')],
            ['start_at' => now(), ...$data],
        )) {
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
        $this->announcement->save();
        $this->overlay(false);
    }
}