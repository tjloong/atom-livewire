<atom:card>
    <atom:_form>
        <atom:_heading>Login Information</atom:_heading>
        <x-input wire:model.defer="user.name" label="Login Name"/>
        <x-input type="email" wire:model.defer="user.email" label="Login Email"/>
        <atom:_button action="submit">Save</atom:_button>
    </atom:_form>
</atom:card>
