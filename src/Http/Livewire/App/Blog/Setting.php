<?php

namespace Jiannius\Atom\Http\Livewire\App\Blog;

use Jiannius\Atom\Traits\Livewire\WithFileInput;
use Jiannius\Atom\Traits\Livewire\WithForm;
use Jiannius\Atom\Traits\Livewire\WithSeo;
use Livewire\Component;

class Setting extends Component
{
    use WithForm;
    use WithFileInput;
    use WithSeo;
    
    public $blog;
    public $inputs;

    /**
     * Validation
     */
    protected function validation(): array
    {
        return [
            'blog.seo' => ['nullable'],
            'blog.cover_id' => ['nullable'],
            'blog.published_at' => ['nullable'],
        ];
    }

    /**
     * Mount
     */
    public function mount(): void
    {
        $this->fill([
            'inputs.status' => $this->blog->status ?? 'draft',
            'inputs.labels' => $this->blog->labels->pluck('id')->toArray(),
        ]);

        $this->setSeo($this->blog->seo);
    }

    /**
     * Updated status
     */
    public function updatedInputsStatus($val): void
    {
        if ($val === 'published' && !$this->blog->published_at) $this->blog->published_at = today();
        if ($val === 'draft') $this->blog->published_at = null;
    }

    /**
     * Submit
     */
    public function submit(): void
    {
        $this->validateForm();

        $this->blog->fill([
            'seo' => $this->seo,
            'cover_id' => $this->blog->cover_id ?? null
        ])->save();

        $this->blog->labels()->sync(data_get($this->inputs, 'labels'));

        $this->emit('submitted');
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.blog.setting');
    }
}