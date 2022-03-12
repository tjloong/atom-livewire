<form wire:submit.prevent="submit">
    <x-box>
        <div class="p-5">
            <x-input.text wire:model.defer="role.name" :error="$errors->first('role.name')" required>
                Role Name
            </x-input.text>

            @if ($role->exists)
                <div class="flex items-center gap-1">
                    <x-icon name="info-circle" size="16px" class="text-gray-400"/>
                    @if ($count > 0)
                        <a href="{{ route('app.user.listing', ['filters' => ['role' => $role->id]]) }}" class="text-xs font-medium">
                            {{ $count }} {{ str('user')->plural($count)}} assigned to this role.
                        </a>
                    @else
                        <span class="text-xs font-medium text-gray-500">
                            No user assigned to this role.
                        </span>
                    @endif
                </div>
            @endif
        </div>

        <x-slot name="buttons">
            <div class="flex justify-between">
                <x-button type="submit" icon="check" color="green">
                    Save
                </x-button>

                @module('permissions')
                    @if ($role->exists)
                        <x-button icon="copy" color="gray" wire:click="duplicate">
                            Duplicate
                        </x-button>
                    @endif
                @endmodule
            </div>
        </x-slot>
    </x-box>
</form>
