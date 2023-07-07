<?php

namespace Jiannius\Atom\Http\Livewire\App\Ticket;

use Jiannius\Atom\Traits\Livewire\WithForm;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;
use Livewire\WithPagination;

class Comments extends Component
{
    use WithForm;
    use WithPagination;
    use WithPopupNotify;

    public $ticket;
    public $content;

    // validation
    protected function validation(): array
    {
        return [
            'content' => ['required' => 'Comment is required.'],
        ];
    }

    // get comments property
    public function getCommentsProperty(): mixed
    {
        return $this->ticket->comments()
            ->orderBy('created_at', 'desc')
            ->paginate(100);
    }

    // submit
    public function submit(): void
    {
        $this->validateForm();

        $comment = $this->ticket->comments()->create(['body' => $this->content]);
        $comment->notify();

        $this->content = null;
        $this->popup('Comment Saved.');
    }

    // delete
    public function delete($id): void
    {
        if ($comment = $this->ticket->comments()->where('created_by', user()->id)->find($id)) {
            $comment->delete();
            $this->popup('Comment Deleted.');
        }
    }

    // render
    public function render(): mixed
    {
        return atom_view('app.ticket.comments');
    }
}