<atom:card>
    <atom:_form>
        <atom:_heading size="lg">Login Information</atom:_heading>
        <atom:_input wire:model.defer="user.name" label="Login Name"/>
        <atom:_input type="email" wire:model.defer="user.email" label="Login Email"/>
        <atom:_button action="submit">Save</atom:_button>
    </atom:_form>
</atom:card>
