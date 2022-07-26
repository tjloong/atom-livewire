<?php

namespace Jiannius\Atom\Http\Livewire\App\Blog\Update;

use Livewire\Component;

class Settings extends Component
{
    public $blog;
    public $status;
    public $selectedLabels;

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
            ->where('type', 'blog-category')
            ->orderBy('name')
            ->get()
            ->map(fn($label) => [
                'value' => $label->id,
                'label' => $label->locale('name'),
            ]);
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
        return view('atom::app.blog.update.settings');
    }
}