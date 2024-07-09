<?php

namespace Jiannius\Atom\Http\Livewire\App;

use Illuminate\Support\Facades\Mail;
use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;
use Livewire\WithFileUploads;

class Sendmail extends Component
{
    use WithFileUploads;
    use WithForm;

    public $email = [];
    public $uploads = [];

    // validation
    protected function validation() : array
    {
        return [
            'email.sender_name' => ['required' => 'Sender name is required.'],
            'email.sender_email' => [
                'required' => 'Sender email is required.',
                'email' => 'Invalid sender email.',
            ],
            'email.to' => [
                'required' => 'To email is required.',
                'array' => 'Invalid to email.',
                'min:1' => 'To email is required.',
            ],
            'email.cc' => ['array' => 'Invalid cc email.'],
            'email.bcc' => ['array' => 'Invalid bcc email.'],
            'email.reply_to' => [
                'nullable',
                'email' => 'Invalid reply-to email',
            ],
            'email.subject' => ['required' => 'Subject is required.'],
            'email.body' => ['required' => 'Body is required.'],
            'email.attachments' => ['nullable'],
        ];
    }

    // updated upload
    public function updatedUploads() : void
    {
        $attachments = collect(get($this->email, 'attachments'));

        foreach ($this->uploads as $upload) {
            $path = storage_path('app/'.$upload->store('mail'));

            $attachments->push([
                'id' => (string) str()->ulid(),
                'filename' => $upload->getClientOriginalName(),
                'path' => $path,
            ]);
        }

        $this->email = [...$this->email, 'attachments' => $attachments->values()->all()];
    }

    // load
    public function load($data = []) : void
    {
        if (($id = get($data, 'id')) && ($model = get($data, 'model'))) {
            $model = str($model)->startsWith('App\\Models')
                ? app($model)->find($id)
                : model($model)->find($id);

            $data = $model->composeEmail(get($data, 'data'));
        }
        else if (($id = get($data, 'share_id')) && ($share = model('share')->find($id))) {
            $data = $share->parent->composeEmail();
        }

        $this->compose($data);
    }

    // compose
    public function compose($data) : void
    {
        $this->email = [
            'sender_name' => null,
            'sender_email' => settings('notify_from'),
            'reply_to' => settings('notify_to'),
            'to' => [],
            'cc' => [],
            'bcc' => [],
            'email_options' => [],
            'subject' => null,
            'body' => null,
            'tags' => [],
            'placeholders' => [],
            ...$data,
        ];

        $this->sanitizeEmailAddress();
        $this->setPlaceholders();
        $this->setAttachments();

    }

    // sanitize email address
    public function sanitizeEmailAddress() : void
    {
        foreach (['to', 'cc', 'bcc'] as $field) {
            $values = get($this->email, $field);
            $list = collect();

            foreach ($values as $val) {
                if (is_string($val)) $list->push(['name' => $val, 'email' => $val]);
                else {
                    // split emails by ; / ,
                    $splits = collect(preg_split('/(;|\/|,)/', get($val, 'email')))->map(fn($val) => trim($val))->filter();

                    foreach ($splits as $split) {
                        $list->push(['name' => get($val, 'name'), 'email' => $split]);
                    }
                }
            }

            $this->email[$field] = $list->unique('email')->values()->all();
        }
    }

    // set placeholders
    public function setPlaceholders() : void
    {
        foreach (['subject', 'body'] as $field) {
            foreach (get($this->email, 'placeholders') as $key => $val) {
                $content = (string) str(get($this->email, $field))->replace('{'.$key.'}', $val);
                $this->email[$field] = $content;
            }
        }
    }

    // set attachments
    public function setAttachments() : void
    {
        $this->email = [...$this->email,
            'attachments' => collect(get($this->email, 'attachments'))->map(function($attachment) {
                $id = (string) str()->ulid();
                $pdf = get($attachment, 'pdf');
                $path = get($attachment, 'path');
                $filename = get($attachment, 'filename', $id);
                
                if ($pdf) {
                    $path = storage_path('app/mail/'.$filename);
                    $pdf->save($path);
                }

                return [
                    'id' => $id,
                    'path' => $path,
                    'filename' => $filename,
                ];
            })->toArray(),
        ];
    }

    // cleanup
    public function cleanup() : void
    {
        collect(get($this->email, 'attachments'))
            ->filter(fn($attachment) => str(get($attachment, 'path'))->is('*/storage/app/mail/*'))
            ->map(fn($attachment) => get($attachment, 'path'))
            ->each(fn($path) => unlink($path));

        $this->reset('email', 'uploads');
    }

    // detach
    public function detach($i) : void
    {
        $attachments = collect(get($this->email, 'attachments'));
        $attachment = $attachments->get($i);
        $attachments->splice($i, 1);
        unlink(get($attachment, 'path'));
        $this->email = [...$this->email, 'attachments' => $attachments->values()->all()];
    }

    // send
    public function send() : void
    {
        $this->validateForm();

        $to = $this->recipients('to');
        $cc = $this->recipients('cc');
        $bcc = $this->recipients('bcc');

        if (!$to) {
            $this->popup([
                'title' => 'app.label.unable-to-sendmail',
                'message' => 'app.label.no-valid-email-recipients',
            ], 'alert', 'error');
        }
        else {
            Mail::to($to)
                ->cc($cc)
                ->bcc($bcc)
                ->send(new \Jiannius\Atom\Mail\Sendmail($this->email));
    
            $this->popup(tr('app.alert.email-sent'));
            $this->overlay(false);
        }
    }

    // get recipients
    public function recipients($field) : array
    {
        return collect(get($this->email, $field))
            ->map(fn($val) => get($val, 'email'))
            ->filter(fn($val) => validator(['email' => $val], ['email' => 'email'])->passes())
            ->values()
            ->all();
    }
}