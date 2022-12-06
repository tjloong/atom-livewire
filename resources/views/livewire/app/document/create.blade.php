<div class="max-w-screen-lg mx-auto">
    <x-page-header :title="$this->title" back/>
    @livewire(lw('app.document.form'), compact('document'))
</div>