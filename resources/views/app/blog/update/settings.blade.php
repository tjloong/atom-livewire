<form wire:submit.prevent="submit">
    <x-box>
        <div class="p-5">
            <x-slot name="header">
                Blog Settings
            </x-slot>

            <x-input.image wire:model="blog.cover_id" dimension="150x100" :placeholder="$blog->cover->url ?? null">
                Cover
            </x-input.image>

            <x-input.select wire:model="status" :options="[
                ['value' => 'draft', 'label' => 'Draft'],
                ['value' => 'published', 'label' => 'Published'],
            ]">
                Status
            </x-input.select>

            <x-input.field>
                <div x-data="{ status: @entangle('status') }" x-show="status === 'published'">
                    <x-input.date wire:model.defer="blog.published_at">
                        Published Date
                    </x-input.date>
                </div>
            </x-input.field>

            <x-input.tags wire:model.defer="selectedLabels" :options="$this->labels">
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
