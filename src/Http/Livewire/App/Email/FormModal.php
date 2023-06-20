<?php

namespace Jiannius\Atom\Http\Livewire\App\Email;

use Jiannius\Atom\Traits\Livewire\WithForm;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class FormModal extends Component
{
    use WithForm;
    use WithPopupNotify;

    public $inputs;
    public $settings;

    protected $listeners = ['open'];

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
    public function open(): void
    {
        $this->inputs = [
            'from' => [
                'name' => data_get($this->settings, 'sender_name'),
                'email' => data_get($this->settings, 'sender_email'),
            ],
            'to' => [head(data_get($this->settings, 'options', []))],
            'cc' => [],
            'bcc' => (array) data_get($this->settings, 'bcc', []),
            'subject' => data_get($this->settings, 'subject'),
            'body' => data_get($this->settings, 'body'),
            'data' => $this->settings,
        ];

        $this->dispatchBrowserEvent('email-modal-open');
    }

    /**
     * Set email placeholder
     */
    // public function setEmailPlaceholder($str = null): mixed
    // {
    //     if (!$str) return $str;

    //     return str($str)
    //         ->replace('{quotation_number}', $this->document->number)
    //         ->replace('{invoice_number}', $this->document->number)
    //         ->replace('{sales_order_number}', $this->document->number)
    //         ->replace('{delivery_order_number}', $this->document->number)
    //         ->replace('{purchase_order_number}', $this->document->number)
    //         ->replace('{bill_number}', $this->document->number)
    //         ->replace('{client_name}', optional($this->document->contact)->name)
    //         ->replace('{client_email}', optional($this->document->contact)->email)
    //         ->replace('{company_name}', $this->document->tenant
    //             ? $this->document->tenant->name
    //             : settings('company'),
    //         )
    //         ->replace('{company_email}', $this->document->tenant
    //             ? $this->document->tenant->email
    //             : settings('email'),
    //         )
    //         ->toString();
    // }

    // submit
    public function submit(): void
    {
        $this->validateForm();

        $email = model('email')->fill($this->inputs);
        $email->save();
        $email->notify();

        $this->emit('emailSent');
        $this->popup('Email Sent.');
        $this->dispatchBrowserEvent('email-modal-close');
    }

    // render
    public function render(): mixed
    {
        return atom_view('app.email.form-modal');
    }
}