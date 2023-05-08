<x-form header="Settings">
    <x-form.group cols="2">
        <x-form.select wire:model="inputs.status" :options="[
            ['value' => 'draft', 'label' => 'Draft'],
            ['value' => 'published', 'label' => 'Published'],
        ]"/>

        @if (data_get($inputs, 'status') === 'published')
            <x-form.date label="Published Date" wire:model.defer="blog.published_at"/>
        @endif

        <x-form.select.label type="blog-category" label="Categories" wire:model="inputs.labels" multiple/>
        <x-form.file wire:model="blog.cover_id" accept="image/*"/>
    </x-form.group>

    <x-form.seo/>
</x-form>
