<?php

namespace Jiannius\Atom\Http\Livewire\App\Blog\Update;

use Jiannius\Atom\Traits\Livewire\WithFile;
use Livewire\Component;

class Settings extends Component
{
    use WithFile;
    
    public $blog;
    public $status;
    public $selectedLabels;

    /**
     * Validation rules
     */
    protected function rules()
    {
        return [
            'blog.cover_id' => 'nullable',
            'blog.published_at' => 'nullable',
        ];
    }

    /**
     * Mount
     */
    public function mount()
    {
        $this->status = $this->blog->status ?? 'draft';
        $this->selectedLabels = $this->blog->labels->pluck('id')->toArray();
    }

    /**
     * Get labels property
     */
    public function getLabelsProperty()
    {
        return model('label')
            ->readable()
            ->where('type', 'blog-category')
            ->orderBy('name')
            ->get();
    }

    /**
     * Updated status
     */
    public function updatedStatus()
    {
        if ($this->status === 'published' && !$this->blog->published_at) $this->blog->published_at = today();
        if ($this->status === 'draft') $this->blog->published_at = null;
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $this->blog->cover_id = $this->blog->cover_id ?: null;
        $this->blog->save();
        $this->blog->labels()->sync($this->selectedLabels);

        $this->emitUp('saved');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.blog.update.settings');
    }
}