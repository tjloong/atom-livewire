<?php

namespace Jiannius\Atom\Http\Livewire\App\Ticket;

use Livewire\Component;
use Livewire\WithPagination;

class Comments extends Component
{
    use WithPagination;

    public $ticket;
    public $content;

    protected $queryString = ['page' => ['except' => 1]];

    protected $rules = ['content' => 'required'];
    protected $messages = ['content.required' => 'Comment is required.'];

    /**
     * Mount
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
        return view('atom::app.ticket.comments', ['comments' => $this->getComments()]);
    }

    /**
     * Post comment
     * 
     * @return void
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
     * Delete comment
     * 
     * @return void
     */
    public function delete($id)
    {
        if ($comment = $this->ticket->comments()->where('created_by', auth()->id())->find($id)) {
            $comment->delete();
            $this->dispatchBrowserEvent('toast', ['message' => 'Comment Deleted']);
        }
    }

    /**
     * Get comments
     * 
     * @return Paginate
     */
    public function getComments()
    {
        return $this->ticket->comments()->orderBy('created_at', 'desc')->paginate(10);
    }
}