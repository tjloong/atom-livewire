<x-form.drawer class="max-w-screen-xl" wire:close="close()">
@if ($blog)
    @if ($blog->exists)
        <x-slot:buttons
            :trash="!$blog->trashed()"
            :delete="$blog->trashed()"
            :restore="$blog->trashed()">
            <x-button action="submit" sm/>
            
            @if ($blog->status === enum('blog.status', 'DRAFT')) <x-button action="publish" wire:click="publish(true)" sm/>
            @else <x-button action="unpublish" wire:click="publish(false)" sm/>
            @endif

            <x-button action="preview" :href="route('web.blog', $blog->slug)" target="_blank" sm/>
        </x-slot:buttons>
    @endif

    <div class="p-5 flex flex-col md:flex-row gap-4">
        <div class="md:w-8/12">
            <x-box>
                <x-group>
                    <input type="text" wire:model.defer="blog.name" placeholder="{{ tr('app.label.title') }}" class="transparent text-2xl font-bold">
                </x-group>

                <x-group>
                    <x-form.text wire:model.defer="blog.description" label="app.label.excerpt"/>
                    <x-editor wire:model="blog.content" label="app.label.content"/>
                </x-group>

                <x-group heading="SEO">
                    <x-form.seo wire:model.defer="inputs.seo"/>
                </x-group>
            </x-box>
        </div>

        <div class="md:w-4/12">
            <x-box>
                <x-group>
                    <x-form.field label="Status">
                        <x-badge :label="$blog->status->value" :color="$blog->status->color()"/>
                    </x-form.field>

                    @if ($blog->status === enum('blog.status', 'PUBLISHED'))
                        <x-form.date wire:model="blog.published_at" label="app.label.publish-date"/>
                    @endif
        
                    <x-form.select.label wire:model="inputs.labels" type="blog-category" multiple/>
                    <x-form.file wire:model="blog.cover_id" label="app.label.cover-image" accept="image/*"/>
                </x-group>
            </x-box>
        </div>
    </div>
@endif
</x-form.drawer>