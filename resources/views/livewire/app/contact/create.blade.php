<div class="max-w-screen-sm mx-auto">
    <x-page-header :title="'Create '.str()->title($contact->type)" back/>
    @livewire(lw('app.contact.form.info'), compact('contact'))
</div>