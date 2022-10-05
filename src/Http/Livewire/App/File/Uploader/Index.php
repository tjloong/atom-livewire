<?php

namespace Jiannius\Atom\Http\Livewire\App\File\Uploader;

use Livewire\Component;
use Jenssegers\Agent\Agent;

class Index extends Component
{
    public $tab;
    public $uid;
    public $title;
    public $multiple;
    public $sources;
    public $accept;
    public $private;
    public $open = false;

    protected $listeners = ['completed'];

    /**
     * Mount
     */
    public function mount(
        $uid = 'uploader',
        $title = 'File Manager',
        $private = false,
        $multiple = false,
        $sources = ['device', 'web-image', 'youtube', 'library'],
        $accept = ['image', 'video', 'audio', 'youtube', 'file']
    ) {
        $this->uid = $uid;
        $this->title = $title;
        $this->private = $private;
        $this->multiple = $multiple;
        $this->sources = $sources;
        $this->accept = $accept;
        $this->tab = $this->tabs->first()['name'];
    }

    /**
     * Get tabs
     */
    public function getTabsProperty()
    {
        $agent = new Agent();
        $tabs = [
            ['name' => 'device', 'label' => $agent->isDesktop() ? 'Computer' : 'Device'],
            ['name' => 'web-image', 'label' => 'Web Image'],
            ['name' => 'youtube', 'label' => 'Youtube'],
            ['name' => 'library', 'label' => 'Library'],
        ];

        return collect($tabs)
            ->filter(fn($tab) => in_array($tab['name'], $this->sources))
            ->values();
    }

    /**
     * Get input file types property
     */
    public function getInputFileTypesProperty()
    {
        $types = [];

        if (in_array('image', $this->accept)) $types = array_merge($types, ['image/png', 'image/jpg', 'image/jpeg', 'image/webp']);
        if (in_array('video', $this->accept)) $types = array_merge($types, ['video/x-flv', 'video/mp4']);
        if (in_array('audio', $this->accept)) $types = array_merge($types, ['audio/mpeg', 'audio/ogg', 'audio/wav']);
        if (in_array('file', $this->accept)) {
            $types = array_merge($types, [
                'application/pdf',
                'application/msword', 
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-powerpoint',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]);
        }

        return $types;
    }

    /**
     * Completed
     */
    public function completed($files)
    {
        $eventname = $this->uid.'-completed';

        $this->emit($eventname, $files);
        $this->dispatchBrowserEvent($eventname, $files);
        $this->dispatchBrowserEvent($this->uid.'-close');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.file.uploader.index');
    }
}