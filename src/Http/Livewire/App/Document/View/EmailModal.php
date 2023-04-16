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

    /**
     * Get settings property
     */
    public function getSettingsProperty(): mixed
    {
        return ($tenant = $this->document->tenant)
            ? tenant('settings.'.$this->document->type.'.email', null, $tenant)
            : settings('document.'.$this->document->type.'.email');
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
     * Open
     */
    public function open(): void
    {
        $this->inputs = [
            'from' => [
                'name' => data_get($this->settings, 'sender_name'),
                'email' => data_get($this->settings, 'sender_email'),
            ],
            'to' => [head(data_get($this->options, 'emails'))],
            'cc' => [],
            'bcc' => (array)(
                data_get($this->settings, 'notify_to')
                ?? data_get($this->settings, 'bcc')
            ),
            'subject' => $this->setEmailPlaceholder(data_get($this->settings, 'subject')),
            'body' => $this->setEmailPlaceholder(data_get($this->settings, 'body')),
        ];

        $this->dispatchBrowserEvent('email-modal-open');
    }

    /**
     * Set email placeholder
     */
    public function setEmailPlaceholder($str = null): mixed
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
            ->replace('{company_name}', $this->document->tenant
                ? $this->document->tenant->name
                : settings('company'),
            )
            ->replace('{company_email}', $this->document->tenant
                ? $this->document->tenant->email
                : settings('email'),
            )
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