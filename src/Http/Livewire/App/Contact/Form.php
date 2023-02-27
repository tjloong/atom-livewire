<?php

namespace Jiannius\Atom\Http\Livewire\App\Contact;

use Jiannius\Atom\Traits\Livewire\WithFile;
use Jiannius\Atom\Traits\Livewire\WithForm;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Form extends Component
{
    use WithFile;
    use WithForm;
    use WithPopupNotify;

    public $contact;

    /**
     * Get title property
     */
    public function getTitleProperty()
    {
        return $this->contact->exists ? str($this->contact->category)->title()->toString().' Information' : null;
    }

    /**
     * Form
     */
    public function form()
    {
        return [
            [
                'contact.category' => [
                    'rules' => ['required' => 'Contact category is required.'],
                ],
                
                'contact.type' => [
                    'label' => 'Contact Type',
                    'readonly' => $this->contact->exists ? str($this->contact->type)->title()->toString() : false,
                    'input' => [
                        ['value' => 'person', 'label' => 'Individual'],
                        ['value' => 'company', 'label' => 'Company'],
                    ],
                    'rules' => $this->contact->exists ? [] : ['required' => 'Contact type is required.'],
                ],
                
                'contact.name' => [
                    'label' => str($this->contact->category)->title()->toString().' Name',
                    'input' => 'text',
                    'rules' => ['required' => 'Name is required.'],
                ],
                
                'contact.owned_by' => $this->contact->exists ? [
                    'label' => 'Owner',
                    'input' => 'select.owner',
                ]: null,

                'contact.avatar_id' => [
                    'label' => $this->contact->type === 'company' ? 'Logo' : 'Avatar',
                    'input' => 'file',
                    'thumbnail' => $this->contact->avatar_id,
                    'attributes' => ['accept' => 'image/*'],
                ],
            ],

            array_merge(
                [
                    'contact.email' => [
                        'label' => 'Email',
                        'input' => 'text',
                    ],
                    'contact.phone' => [
                        'label' => 'Phone',
                        'input' => 'text',
                    ],
                ],

                $this->contact->type === 'company' ? [
                    'contact.fax' => [
                        'label' => 'Fax',
                        'input' => 'text',
                    ],

                    'contact.brn' => [
                        'label' => 'Business Registration Number',
                        'input' => 'text',
                    ],
        
                    'contact.tax_number' => [
                        'label' => 'Tax Number',
                        'input' => 'text',
                    ],

                    'contact.website' => [
                        'label' => 'Website',
                        'input' => 'text',
                    ],
                ] : [],
            ),

            [
                'contact.address_1' => [
                    'label' => 'Address Line 1',
                    'input' => 'text',
                ],

                'contact.address_2' => [
                    'label' => 'Address Line 2',
                    'input' => 'text',
                ],

                'contact.city' => [
                    'label' => 'City',
                    'input' => 'text',
                ],

                'contact.zip' => [
                    'label' => 'Postcode',
                    'input' => 'text',
                ],

                'contact.country' => [
                    'input' => 'select.country',
                ],

                'contact.state' => [
                    'input' => 'select.state',
                    'attributes' => ['country' => $this->contact->country, 'uid' => uniqid()],
                ],
            ],
        ];
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $this->contact->save();
        $this->submitted();
    }

    /**
     * Submitted
     */
    public function submitted()
    {
        if ($this->contact->wasRecentlyCreated) return redirect()->route('app.contact.view', [$this->contact->id]);
        $this->popup('Contact Updated.');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.contact.form');
    }
}