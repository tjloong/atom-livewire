<?php

namespace App\Http\Livewire\Web;

use Livewire\Component;

class Home extends Component
{
    public $banners;

    /**
     * Mount event
     * 
     * @return void
     */
    public function mount()
    {
        //
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('livewire.web.home', [
            'faq' => [
                [
                    'question' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
                    'answer' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Doloribus ab laboriosam, laborum deserunt aperiam, soluta provident voluptas repudiandae ut ducimus esse quis! Quod, incidunt! Accusantium ducimus veritatis reiciendis deserunt sequi?',
                ],
                [
                    'question' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
                    'answer' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Doloribus ab laboriosam, laborum deserunt aperiam, soluta provident voluptas repudiandae ut ducimus esse quis! Quod, incidunt! Accusantium ducimus veritatis reiciendis deserunt sequi?',
                ],
            ]
        ])->layout('layouts.web');
    }
}