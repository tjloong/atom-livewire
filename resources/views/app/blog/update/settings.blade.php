<x-form label="Blog Settings">
    <x-form.image 
        label="Cover"
        wire:model="blog.cover_id" 
        dimension="150x100" 
        :placeholder="$blog->cover->url ?? null"
    />

    <x-form.select 
        label="Status"
        wire:model="status" 
        :options="[
            ['value' => 'draft', 'label' => 'Draft'],
            ['value' => 'published', 'label' => 'Published'],
        ]"
    />

    <div x-data="{ status: @entangle('status') }" x-show="status === 'published'">
        <x-form.date 
            label="Published Date"
            wire:model.defer="blog.published_at"
        />
    </div>

    <x-form.picker 
        label="Categories"
        wire:model="selectedLabels" 
        :options="$this->labels"
        :selected="$this->selectedLabels"
        multiple
    />

    <x-slot:foot>
        <x-button.submit/>
    </x-slot:foot>
</x-form>
