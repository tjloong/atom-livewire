<x-page submit wire:close="$emit('closeBlog')">
@if ($blog)
    <x-slot:buttons>
        @if ($blog->trashed())
            <x-button action="restore"/>
            <x-button action="delete" no-label invert/>
        @else
            <x-button action="submit"/>

            @if ($blog->exists)
                @if ($blog->status === enum('blog.status', 'DRAFT')) <x-button action="publish" wire:click="publish(true)"/>
                @else <x-button action="unpublish" wire:click="publish(false)"/>
                @endif

                <x-button action="preview" :href="route('web.blog', $blog->slug)" target="_blank"/>
                <x-button action="trash" no-label invert/>
            @endif
        @endif
    </x-slot:buttons>

    <div class="flex flex-col md:flex-row gap-4">
        <div class="grow">
            <x-box>
                <div class="flex flex-col divide-y">
                    <div class="p-3">
                        <x-input wire:model.defer="blog.name"
                            placeholder="app.label.title"
                            transparent xl no-label class="font-bold">
                        </x-input>
                    </div>

                    <x-inputs>
                        <x-input wire:model.defer="blog.description" label="app.label.excerpt"/>
                        <x-editor wire:model="blog.content" label="app.label.content"/>
                    </x-inputs>

                    <x-inputs title="SEO">
                        <x-form.seo wire:model.defer="inputs.seo"/>
                    </x-inputs>
                </div>
            </x-box>
        </div>

        <div class="shrink-0 md:w-4/12">
            <x-box>
                <x-inputs>
                    <x-field label="Status" block>
                        <x-badge :badge="$blog->status->badge()"/>
                    </x-field>

                    <x-date-picker wire:model="blog.published_at" label="app.label.publish-date"/>
                    <x-select wire:model="inputs.labels" options="labels.blog-category" multiple/>
                    <x-file-input wire:model="blog.cover_id" label="app.label.cover-image" accept="image/*"/>
                </x-inputs>
            </x-box>
        </div>
    </div>
@endif
</x-form.drawer>