<?php

namespace Jiannius\Atom\Http\Livewire\App\Ticketing;

use Livewire\Component;
use Livewire\WithPagination;

class Comments extends Component
{
    use WithPagination;

    public $ticket;
    public $content;

    protected $queryString = [
        'page' => ['except' => 1]
    ];

    /**
     * Validation rules
     */
    protected function rules()
    {
        return [
            'content' => 'required',
        ];
    }

    /**
     * Validation messages
     */
    protected function messages()
    {
        return [
            'content.required' => 'Comment is required.',
        ];
    }

    /**
     * Mount
     */
    public function mount()
    {
        //
    }

    /**
     * Get comments property
     */
    public function getCommentsProperty()
    {
        return $this->ticket->comments()
            ->orderBy('created_at', 'desc')
            ->paginate(30);
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $comment = $this->ticket->comments()->create(['body' => $this->content]);
        $comment->notify();

        $this->content = null;
        $this->dispatchBrowserEvent('toast', ['message' => 'Comment Saved', 'type' => 'success']);
    }

    /**
     * Delete
     */
    public function delete($id)
    {
        if ($comment = $this->ticket->comments()->where('created_by', auth()->id())->find($id)) {
            $comment->delete();
            $this->dispatchBrowserEvent('toast', ['message' => 'Comment Deleted']);
        }
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.ticketing.comments');
    }
}