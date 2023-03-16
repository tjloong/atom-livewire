<?php

namespace Jiannius\Atom\Http\Livewire\App\Document\View;

use Jiannius\Atom\Traits\Livewire\WithForm;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class EmailModal extends Component
{
    use WithForm;
    use WithPopupNotify;

    public $inputs;
    public $document;

    protected $listeners = ['open'];

    /**
     * Validation
     */
    protected function validation(): array
    {
        return [
            'email.from.name' => ['required' => 'Sender name is required.'],
            'email.from.email' => [
                'required' => 'Sender email is required.',
                'email' => 'Invalid sender email.',
            ],
            'email.to' => [
                'required' => 'To email is required.',
                'array' => 'Invalid to email.',
                'min:1' => 'To email is required.',
            ],
            'email.to.*' => [
                'email' => 'Invalid to email.'
            ],
            'email.cc' => ['array' => 'Invalid cc email.'],
            'email.cc.*' => ['email' => 'Invalid cc email.'],
            'email.bcc' => ['array' => 'Invalid bcc email.'],
            'email.bcc.*' => ['email' => 'Invalid bcc email.'],
            'email.subject' => ['required' => 'Subject is required.'],
            'email.body' => ['required' => 'Body is required.'],
        ];
    }

    /**
     * Open
     */
    public function open(): void
    {
        $settings = model('document')->enabledHasTenantTrait
            ? tenant('settings.'.$this->document->type.'.email')
            : settings('document.'.$this->document->type.'.email');

        $this->inputs = [
            'from' => [
                'name' => data_get($settings, 'sender_name'),
                'email' => data_get($settings, 'sender_email'),
            ],
            'to' => head(data_get($this->options, 'emails')),
            'cc' => [],
            'bcc' => (array)data_get($settings, 'notify_to'),
            'subject' => $this->setEmailPlaceholder(data_get($settings, 'subject')),
            'body' => $this->setEmailPlaceholder(data_get($settings, 'body')),
        ];

        $this->dispatchBrowserEvent('email-modal-open');
    }

    /**
     * Get options property
     */
    public function getOptionsProperty(): array
    {
        return [
            'emails' => ($contact = $this->document->contact)
                ? collect()
                    ->concat([$contact->email])
                    ->concat($contact->persons->pluck('email')->toArray())
                    ->toArray()
                : [],
        ];
    }

    /**
     * Set email placeholder
     */
    public function setEmailPlaceholder($str = null): string
    {
        if (!$str) return $str;

        return str($str)
            ->replace('{quotation_number}', $this->document->number)
            ->replace('{invoice_number}', $this->document->number)
            ->replace('{sales_order_number}', $this->document->number)
            ->replace('{delivery_order_number}', $this->document->number)
            ->replace('{purchase_order_number}', $this->document->number)
            ->replace('{bill_number}', $this->document->number)
            ->replace('{client_name}', optional($this->document->contact)->name)
            ->replace('{client_email}', optional($this->document->contact)->email)
            ->toString();
    }

    /**
     * Submit
     */
    public function submit(): void
    {
        $this->validateForm();

        $email = model('document_email')->fill(array_merge($this->inputs, [
            'document_id' => $this->document->id,
        ]));

        $email->save();
        $email->notify();

        $this->document->fill(['last_sent_at' => now()])->saveQuietly();

        $this->emit('refresh');
        $this->popup('Email Sent.');
        $this->dispatchBrowserEvent('email-modal-close');
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.document.view.email-modal');
    }
}