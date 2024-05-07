<?php

namespace Jiannius\Atom\Http\Livewire\App;

use Illuminate\Support\Facades\Notification;
use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;
use Livewire\WithFileUploads;

class SendMail extends Component
{
    use WithFileUploads;
    use WithForm;

    public $inputs;
    public $uploads = [];
    public $options;    // email address options

    protected $listeners = [
        'sendMail' => 'open',
    ];

    // validation
    protected function validation() : array
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
            'inputs.to.*.email' => [
                'email' => 'Invalid to email.'
            ],
            'inputs.cc' => ['array' => 'Invalid cc email.'],
            'inputs.cc.*.email' => ['email' => 'Invalid cc email.'],
            'inputs.bcc' => ['array' => 'Invalid bcc email.'],
            'inputs.bcc.*.email' => ['email' => 'Invalid bcc email.'],
            'inputs.reply_to' => [
                'nullable',
                'email' => 'Invalid reply-to email',
            ],
            'inputs.subject' => ['required' => 'Subject is required.'],
            'inputs.body' => ['required' => 'Body is required.'],
            'inputs.attachments' => ['nullable'],
        ];
    }

    // updated upload
    public function updatedUploads() : void
    {
        foreach ($this->uploads as $upload) {
            $this->inputs['attachments'] = collect($this->inputs['attachments'])->push([
                'id' => (string) str()->ulid(),
                'name' => $upload->getClientOriginalName(),
                'upload_id' => $upload->getFilename(),
            ]);
        }
    }

    // open
    public function open($data = [], $options = []) : void
    {
        $this->options = $this->setEmailList($options);

        $this->inputs = [
            'from' => ['name' => null, 'email' => null],
            'reply_to' => null,
            'to' => [],
            'cc' => [],
            'bcc' => [],
            'subject' => null,
            'body' => null,
            'attachments' => [],
            ...$data,
        ];

        if (!get($this->inputs, 'to') && $this->options) {
            $this->inputs['to'] = $this->options;
        }

        $this->inputs['to'] = $this->setEmailList($this->inputs['to']);
        $this->inputs['cc'] = $this->setEmailList($this->inputs['cc']);
        $this->inputs['bcc'] = $this->setEmailList($this->inputs['bcc']);

        $this->setPlaceholders();
        $this->openDrawer();
    }

    // close
    public function close() : void
    {
        $this->closeDrawer();
    }

    // get recipients
    public function getRecipients() : array
    {
        return collect(data_get($this->inputs, 'to'))
            ->mapWithKeys(fn($val) => [
                data_get($val, 'email') => data_get($val, 'name'),
            ])
            ->toArray();
    }

    // set placeholders
    public function setPlaceholders() : void
    {
        $subject = data_get($this->inputs, 'subject');
        $body = data_get($this->inputs, 'body');
        $placeholders = data_get($this->inputs, 'placeholders', []);

        foreach ($placeholders as $key => $val) {
            $subject = str($subject)->replace('{'.$key.'}', $val)->toString();
            $body = str($body)->replace('{'.$key.'}', $val)->toString();
        }

        $this->fill([
            'inputs.subject' => $subject,
            'inputs.body' => $body,
        ]);
    }

    // set email list
    public function setEmailList($list) : array
    {
        $emails = collect();

        foreach ($list as $item) {
            if (is_string($item)) $emails->push(['name' => $item, 'email' => $item]);
            else {
                $split = collect(preg_split('/(;|\/|,)/', get($item, 'email')))->map(fn($val) => trim($val));

                foreach ($split as $value) {
                    $emails->push(['name' => get($item, 'name'), 'email' => $value]);
                }
            }
        }

        return $emails->unique('email')->values()->all();
    }

    // store attachments
    public function storeAttachments() : void
    {
        $this->inputs['attachments'] = collect($this->inputs['attachments'])->map(function($attachment) {
            $uploadId = data_get($attachment, 'upload_id');
            $upload = collect($this->uploads)->first(fn($val) => $val->getFilename() === $uploadId);

            if ($upload) {
                $path = $upload->store('mail');
                $path = storage_path('app/'.$path);
                data_set($attachment, 'path', $path);
            }

            return $attachment;
        })->toArray();
    }

    // submit
    public function submit() : void
    {
        $this->validateForm();
        $this->storeAttachments();

        Notification::route('mail', $this->getRecipients())->notify(
            new \Jiannius\Atom\Notifications\SendMail($this->inputs)
        );

        $this->popup(tr('app.alert.notification-sent', ['channel' => 'Email']));
        $this->emit('mailSent');
        $this->close();
    }
}