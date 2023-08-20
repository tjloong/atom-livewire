<?php

namespace Jiannius\Atom\Http\Livewire\App;

use Illuminate\Support\Facades\Notification;
use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;

class SendEmail extends Component
{
    use WithForm;
    use WithPopupNotify;

    public $inputs;
    public $emails;

    protected $listeners = [
        'sendEmail' => 'open',
    ];

    // validation
    protected function validation(): array
    {
        return [
            'inputs.from.name' => ['required' => 'Sender name is required.'],
            'inputs.from.email' => [
                'required' => 'Sender email is required.',
                'email' => 'Invalid sender email.',
            ],
            'inputs.to' => [
                'required' => 'To email is required.',
                'array' => 'Invalid to email.',
                'min:1' => 'To email is required.',
            ],
            'inputs.to.*' => [
                'email' => 'Invalid to email.'
            ],
            'inputs.cc' => ['array' => 'Invalid cc email.'],
            'inputs.cc.*' => ['email' => 'Invalid cc email.'],
            'inputs.bcc' => ['array' => 'Invalid bcc email.'],
            'inputs.bcc.*' => ['email' => 'Invalid bcc email.'],
            'inputs.subject' => ['required' => 'Subject is required.'],
            'inputs.body' => ['required' => 'Body is required.'],
        ];
    }

    // open
    public function open($settings = null): void
    {
        $this->emails = data_get($settings, 'emails', []);

        $this->inputs = [
            'from' => [
                'name' => data_get($settings, 'sender_name'),
                'email' => data_get($settings, 'sender_email'),
            ],
            'to' => array_filter([collect($this->emails)->shift()]),
            'cc' => [],
            'bcc' => [],
            'subject' => $this->setPlaceholders(
                data_get($settings, 'subject'),
                data_get($settings, 'placeholders'),
            ),
            'body' => $this->setPlaceholders(
                data_get($settings, 'body'),
                data_get($settings, 'placeholders'),
            ),
            'attachment' => data_get($settings, 'attachment'),
        ];

        $this->dispatchBrowserEvent('send-email-open');
    }

    // close
    public function close() : void
    {
        $this->emit('emailSent');
        $this->dispatchBrowserEvent('send-email-close');
    }

    // set placeholders
    public function setPlaceholders($content, $placeholders) : mixed
    {
        if (!$content) return $content;

        $content = str($content);

        foreach ($placeholders as $key => $val) {
            $content = $content->replace('{'.$key.'}', $val);
        }

        return $content->toString();
    }

    // submit
    public function submit(): void
    {
        $this->validateForm();

        Notification::route('mail', data_get($this->inputs, 'to'))
            ->notify(new \Jiannius\Atom\Notifications\SendEmailNotification($this->inputs));

        $this->popup('Email Sent.');
        $this->close();
    }
}