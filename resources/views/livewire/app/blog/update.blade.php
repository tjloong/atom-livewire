<x-form.drawer id="blog-update" class="max-w-screen-xl p-5" wire:close="$emit('setBlogId')">
@if ($blog)
    <x-slot:buttons :delete="$blog->exists">
        <x-button.submit sm/>

        @if ($blog->status === enum('blog.status', 'DRAFT'))
            <x-button icon="upload" label="blog.button.publish" sm
                wire:click="publish(true)"/>
        @else
            <x-button icon="undo" label="blog.button.unpublish" sm
                wire:click="publish(false)"/>
        @endif

        @if ($blog->exists)
            <x-button icon="eye" label="blog.button.preview" sm
                href="{{ route('web.blog', $blog->slug) }}"
                target="_blank"/>
        @endif
    </x-slot:buttons>

    <div class="flex flex-col md:flex-row gap-4">
        <div class="md:w-8/12">
            <x-box>
                <x-form.group>
                    <input type="text" placeholder="{{ __('blog.label.title') }}"
                        wire:model.defer="blog.name"
                        class="transparent text-2xl font-bold">
                </x-form.group>

                <x-form.group>
                    <x-form.text label="blog.label.excerpt"
                        wire:model.defer="blog.description"/>

                    <x-form.editor label="blog.label.content"
                        wire:model="blog.content"/>
                </x-form.group>
            </x-box>
        </div>

        <div class="md:w-4/12">
            <x-box>
                <x-form.group>
                    @if ($blog->status === enum('blog.status', 'PUBLISHED'))
                        <x-form.date label="blog.label.publish-date" 
                            wire:model="blog.published_at"/>
                    @endif
        
                    <x-form.select.label type="blog-category" multiple
                        label="common.label.category"
                        placeholder="common.label.select-category"
                        wire:model="inputs.labels"/>

                    <x-form.file label="blog.label.cover" accept="image/*"
                        wire:model="blog.cover_id"/>
                </x-form.group>
            </x-box>
        </div>
    </div>
@endif
</x-form.drawer>