<div class="max-w-screen-lg mx-auto">
    <x-page-header :title="$this->title" back/>
    @livewire(atom_lw('app.document.form'), compact('document'))
</div>