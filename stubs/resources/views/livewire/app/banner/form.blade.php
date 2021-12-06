<form wire:submit.prevent="save">
    <x-box>
        <div class="p-5">
            <x-input.text wire:model.defer="banner.name" required :error="$errors->first('banner.name')">
                Banner Name
            </x-input.text>

            <x-input.text wire:model.defer="banner.url">
                Link
            </x-input.text>

            <x-input.date wire:model.defer="banner.start_at">
                Start Date
            </x-input.date>

            <x-input.date wire:model.defer="banner.end_at">
                End Date
            </x-input.date>

            <x-input.select
                wire:model.defer="banner.type"
                required
                :error="$errors->first('banner.type')"
                :options="[
                    ['value' => 'leaderboard', 'label' => 'Leaderboard (728x90)'],
                    ['value' => 'billboard', 'label' => 'Billboard (970x250)'],
                    ['value' => 'rectangle', 'label' => 'Rectangle (336x280)'],
                    ['value' => 'square', 'label' => 'Square (250x250)'],
                ]"
            >
                Banner Type
            </x-input.select>

            <x-input.image wire:model.defer="banner.image_id" :placeholder="$banner->image->url ?? null">
                Banner Image
            </x-input.image>

            <x-input.checkbox wire:model.defer="banner.is_active">
                This banner is active
            </x-input.checkbox>
        </div>

        <x-slot name="buttons">
            <x-button type="submit" color="green" icon="check">
                Save Banner
            </x-button>
        </x-slot>
    </x-box>
</form>
