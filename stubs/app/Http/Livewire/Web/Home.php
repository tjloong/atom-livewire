<?php

namespace App\Http\Livewire\Web;

use Livewire\Component;
use App\Models\SiteSetting;

class Home extends Component
{
    public $faq;
    public $contact;

    /**
     * Mount event
     * 
     * @return void
     */
    public function mount()
    {
        $this->faq = [
            [
                'question' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
                'answer' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Doloribus ab laboriosam, laborum deserunt aperiam, soluta provident voluptas repudiandae ut ducimus esse quis! Quod, incidunt! Accusantium ducimus veritatis reiciendis deserunt sequi?',
            ],
            [
                'question' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
                'answer' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Doloribus ab laboriosam, laborum deserunt aperiam, soluta provident voluptas repudiandae ut ducimus esse quis! Quod, incidunt! Accusantium ducimus veritatis reiciendis deserunt sequi?',
            ],
        ];

        $this->contact = [];
        SiteSetting::contact()->get()->each(fn($setting) => $this->contact[$setting->name] = $setting->value);
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('livewire.web.home')->layout('layouts.web');
    }
}