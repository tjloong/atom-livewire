<div class="max-w-screen-md mx-auto">
    <x-page-header :title="$person->name" back/>
    @livewire(lw('app.contact.person.form'), compact('person'))
</div>