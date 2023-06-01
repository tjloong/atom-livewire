<div class="max-w-screen-md mx-auto">
    <x-page-header :title="$person->name" back/>
    @livewire(atom_lw('app.contact.person.form'), compact('person'))
</div>