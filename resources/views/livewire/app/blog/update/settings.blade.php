<x-form label="Blog Settings">
    <x-form.field label="Cover">
        @if ($blog->cover)
            <x-thumbnail
                :url="$blog->cover->url"
                wire:remove="$set('blog.cover_id', null)"
            />
        @else
            <x-form.file
                wire:model="blog.cover_id"
                accept="image/*"
            />
        @endif
    </x-form.field>

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

    <x-form.select 
        label="Categories"
        wire:model="selectedLabels" 
        :options="$this->labels"
        multiple
    />

    <x-slot:foot>
        <x-button.submit/>
    </x-slot:foot>
</x-form>
