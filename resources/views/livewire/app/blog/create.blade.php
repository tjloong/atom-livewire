<div class="max-w-screen-lg mx-auto">
    <x-page-header :title="$this->title" back/>
    @livewire(atom_lw('app.blog.form'), compact('blog'))
</div>