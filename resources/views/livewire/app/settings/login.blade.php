<div class="max-w-screen-md">
    <x-page-header title="Login Information"/>

    @json($currentRouteName)
    @json($currentUrl)

    <x-form>
        @if ($this->isLoginMethod('username') && $this->isLoginMethod('email'))
            <x-form.group cols="2">
                <x-form.text wire:model.defer="user.name" label="Login Name"/>
            </x-form.group>

            <x-form.group cols="2">
                <x-form.text wire:model.defer="user.username"/>
                <x-form.email wire:model.defer="user.email" label="Login Email"/>
            </x-form.group>
        @else
            <x-form.group cols="2">
                <x-form.text wire:model.defer="user.name" label="Login Name"/>

                @if ($this->isLoginMethod('email'))
                    <x-form.email wire:model.defer="user.email" label="Login Email"/>
                @elseif ($this->isLoginMethod('username'))
                    <x-form.text wire:model.defer="user.username"/>
                @endif
            </x-form.group>
        @endif
    </x-form>
</div>
