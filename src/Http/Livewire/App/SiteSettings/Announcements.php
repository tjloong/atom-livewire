<?php

namespace Jiannius\Atom\Http\Livewire\App\SiteSettings;

use Livewire\Component;

class Announcements extends Component
{
    public $form;
    public $announcements;

    /**
     * Validation rules
     */
    protected function rules()
    {
        return [
            'form.type' => 'required',
            'form.content' => 'required',
            'form.is_active' => 'nullable',
        ];
    }

    /**
     * Validation messages
     */
    protected function messages()
    {
        return [
            'form.type.required' => __('Announcement type is required.'),
            'form.content.required' => __('Announcement content is required.'),
        ];
    }

    /**
     * Mount
     */
    public function mount()
    {
        $this->announcements = collect(json_decode(site_settings('announcements')));
    }

    /**
     * Get types property
     */
    public function getTypesProperty()
    {
        return collect([
            ['value' => 'general', 'label' => 'General'],
        ]);
    }

    /**
     * Updated announcements
     */
    public function updatedAnnouncements()
    {
        $this->emitUp('submit', ['announcements' => $this->announcements]);
    }

    /**
     * Create
     */
    public function create()
    {
        $this->form = [
            'uid' => null,
            'url' => null,
            'type' => data_get($this->types->first(), 'value'),
            'content' => null,
            'is_active' => false,
        ];

        $this->dispatchBrowserEvent('modal-open');
    }

    /**
     * Edit
     */
    public function edit($uid)
    {
        $this->form = collect($this->announcements)->firstWhere('uid', $uid);
        $this->dispatchBrowserEvent('modal-open');
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $announcements = collect($this->announcements);

        if ($uid = $this->form['uid'] ?? null) {
            $announcements = $announcements->map(fn($val) => data_get($val, 'uid') === $uid
                ? $this->form
                : $val
            );
        }
        else $announcements->push(array_merge($this->form, ['uid' => uniqid(str()->random(4))]));

        $this->emitUp('submit', ['announcements' => $announcements]);
        
        return redirect($this->redirectTo());
    }

    /**
     * Delete
     */
    public function delete($uid)
    {
        $announcements = collect($this->announcements)
            ->reject(fn($val) => data_get($val, 'uid') === $uid)
            ->values();

        $this->emitUp('submit', ['announcements' => $announcements]);

        return redirect($this->redirectTo());
    }

    /**
     * Redirect to
     */
    public function redirectTo()
    {
        return route('app.site-settings', ['tab' => 'announcements']);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.site-settings.announcements');
    }
}