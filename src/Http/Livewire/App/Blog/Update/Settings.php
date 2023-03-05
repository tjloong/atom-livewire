<?php

namespace Jiannius\Atom\Http\Livewire\App\Blog\Update;

use Jiannius\Atom\Traits\Livewire\WithFile;
use Jiannius\Atom\Traits\Livewire\WithForm;
use Livewire\Component;

class Settings extends Component
{
    use WithForm;
    use WithFile;
    
    public $blog;
    public $status;
    public $selectedLabels;

    /**
     * Validation
     */
    protected function validation(): array
    {
        return [
            'blog.cover_id' => ['nullable'],
            'blog.published_at' => ['nullable'],
        ];
    }

    /**
     * Mount
     */
    public function mount(): void
    {
        $this->status = $this->blog->status ?? 'draft';
        $this->selectedLabels = $this->blog->labels->pluck('id')->toArray();
    }

    /**
     * Updated status
     */
    public function updatedStatus(): void
    {
        if ($this->status === 'published' && !$this->blog->published_at) $this->blog->published_at = today();
        if ($this->status === 'draft') $this->blog->published_at = null;
    }

    /**
     * Submit
     */
    public function submit(): void
    {
        $this->validateForm();

        $this->blog->cover_id = $this->blog->cover_id ?: null;
        $this->blog->save();
        $this->blog->labels()->sync($this->selectedLabels);

        $this->emitUp('saved');
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.blog.update.settings');
    }
}