<?php

namespace Jiannius\Atom\Traits\Livewire;

trait WithPopupNotify
{
    public function popup($body, $type = 'toast', $alert = 'info')
    {
        $body = [
            'title' => is_string($body)
                ? null
                : __(data_get($body, 'title')),
            
            'message' => is_string($body)
                ? $body
                : __(data_get($body, 'message')),

            'type' => $alert,
        ];

        $this->dispatchBrowserEvent($type, $body);
    }
}