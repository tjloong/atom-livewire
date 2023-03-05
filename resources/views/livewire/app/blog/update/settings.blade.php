<x-form header="Blog Settings">
    <x-form.group>
        <x-form.field label="Cover">
            @if ($blog->cover_id) <x-thumbnail :file="$blog->cover_id" wire:remove="$set('blog.cover_id', null)"/>
            @else <x-form.file wire:model="blog.cover_id" accept="image/*"/>
            @endif
        </x-form.field>
    
        <x-form.select wire:model="status" :options="[
            ['value' => 'draft', 'label' => 'Draft'],
            ['value' => 'published', 'label' => 'Published'],
        ]"/>
    
        <div x-data="{ status: @entangle('status') }" x-show="status === 'published'">
            <x-form.date label="Published Date" wire:model.defer="blog.published_at"/>
        </div>
    
        <x-form.select.label type="blog-category" label="Categories" wire:model="selectedLabels" multiple/>
    </x-form.group>
</x-form>
