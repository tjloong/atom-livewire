<form wire:submit.prevent="save" class="max-w-lg">
    <x-box>
        <div class="p-5">
            <x-slot name="header">
                Blog Settings
            </x-slot>

            <x-input.image wire:model="blog.cover_id" dimension="150x100" :placeholder="$blog->cover->url ?? null">
                Cover
            </x-input.image>

            <x-input.select wire:model="status" :options="[
                (object)['value' => 'draft', 'label' => 'Draft'],
                (object)['value' => 'published', 'label' => 'Published'],
            ]">
                Status
            </x-input.select>

            <x-input.field>
                @if ($status === 'published')
                    <x-input.date wire:model.defer="blog.published_at">
                        Published Date
                    </x-input.date>
                @endif
            </x-input.field>

            <x-input.tags wire:model.defer="labels" :options="$labelOptions">
                Categories
            </x-input.tags>
        </div>

        <x-slot name="buttons">
            <x-button type="submit" color="green" icon="check">
                Save Blog Settings
            </x-button>
        </x-slot>
    </x-box>
</form>
