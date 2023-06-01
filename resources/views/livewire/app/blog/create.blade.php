<div class="max-w-screen-lg mx-auto">
    <x-page-header :title="$this->title" back="auto"/>
    @livewire(atom_lw('app.blog.form'), compact('blog'))
</div>