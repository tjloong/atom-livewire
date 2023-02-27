<?php

namespace Jiannius\Atom\Http\Livewire\App\Document\Form;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class EmailModal extends Component
{
    use WithPopupNotify;

    public $email;
    public $document;

    protected $listeners = ['open'];

    /**
     * Get emails property
     */
    public function getEmailsProperty()
    {
        $emails = collect();

        if ($contact = $this->document->contact) {
            $emails = $emails
                ->concat([$contact->email])
                ->concat($contact->persons->pluck('email')->toArray())
                ->values();
        }

        return $emails;
    }

    /**
     * Open
     */
    public function open()
    {
        $this->setEmail();
        $this->dispatchBrowserEvent('email-form-modal-open');
    }

    /**
     * Set email
     */
    public function setEmail()
    {
        $settings = model('document')->enabledHasTenantTrait
            ? tenant_settings($this->document->type.'.email')
            : settings('app.document.'.$this->document->type.'.email');

        $this->email = [
            'from' => [
                'name' => data_get($settings, 'sender_name'),
                'email' => data_get($settings, 'sender_email'),
            ],
            'to' => (array)$this->emails->first(),
            'cc' => [],
            'bcc' => (array)data_get($settings, 'notify_to'),
            'subject' => $this->setEmailPlaceholder(data_get($settings, 'subject')),
            'body' => $this->setEmailPlaceholder(data_get($settings, 'body')),
        ];
    }

    /**
     * Set email placeholder
     */
    public function setEmailPlaceholder($str = null)
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
            ->replace('{company_name}', auth()->user()->tenant->name)
            ->replace('{company_email}', auth()->user()->tenant->email)
            ->toString();
    }

    /**
     * Send email
     */
    public function sendEmail()
    {
        $this->resetValidation();
        $this->validate(
            [
                'email.from.name' => 'required',
                'email.from.email' => 'required|email',
                'email.to' => 'required|array|min:1',
                'email.to.*' => 'email',
                'email.cc' => 'array',
                'email.cc.*' => 'email',
                'email.bcc' => 'array',
                'email.bcc.*' => 'email',
                'email.subject' => 'required',
                'email.body' => 'required',
            ],
            [
                'email.from.name.required' => __('Sender name is required.'),
                'email.from.email.required' => __('Sender email is required.'),
                'email.from.email.email' => __('Invalid sender email.'),
                'email.to.required' => __('To email is required.'),
                'email.to.array' => __('Invalid to email.'),
                'email.to.min:1' => __('To email is required.'),
                'email.to.*.email' => __('Invalid to email.'),
                'email.cc.array' => __('Invalid cc email.'),
                'email.cc.*.email' => __('Invalid cc email.'),
                'email.bcc.array' => __('Invalid bcc email.'),
                'email.bcc.*.email' => __('Invalid bcc email.'),
                'email.subject.required' => __('Subject is required.'),
                'email.body.required' => __('Body is required.'),
            ]
        );

        $email = model('document_email')
            ->fill($this->email)
            ->fill(['document_id' => $this->document->id]);

        $email->save();
        $email->notify();

        $this->document->fill(['last_sent_at' => now()])->saveQuietly();

        $this->emit('refresh');
        $this->popup('Email Sent.');
        $this->dispatchBrowserEvent('email-form-modal-close');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.document.form.email-modal');
    }
}