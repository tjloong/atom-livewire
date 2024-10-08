<atom:sheet name="settings" wire:open="">
    <atom:_heading size="xl">Settings</atom:_heading>

    <div class="flex flex-col gap-6 md:flex-row">
        <div class="shrink-0 md:w-72">
            <atom:menu wire:model="tab">
                <atom:menu-item value="profile" icon="member">Profile</atom:menu-item>
                <atom:menu-item value="user" icon="users">Users</atom:menu-item>
                <atom:menu-item value="file" icon="image">Files and Media</atom:menu-item>
                <atom:subheading size="sm">SYSTEM</atom:subheading>
                <atom:menu-item value="integration/email" icon="at">Email</atom:menu-item>
                <atom:menu-item value="integration/storage" icon="database">Storage</atom:menu-item>
            </atom:menu>
        </div>
    
        <div class="grow">
            @if ($this->component)
                @livewire(
                    $this->component->path,
                    $this->component->params ?? [],
                    key($this->component->key)
                )
            @endif
        </div>
    </div>
</atom:sheet>
