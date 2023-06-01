<div class="max-w-screen-lg mx-auto">
    <x-page-header title="Update Blog" back>
        <x-button color="gray" icon="show" target="_blank" 
            label="Preview"
            href="/blog/{{ $blog->slug }}?preview=1"
        />

        <x-button.delete inverted
            title="Delete Blog"
            message="Are you sure to delete this blog?"
        />
    </x-page-header>

    <div class="flex flex-col gap-6">
        @livewire(atom_lw('app.blog.form'), compact('blog'), key('form'))
        @livewire(atom_lw('app.blog.setting'), compact('blog'), key('setting'))
    </div>
</div>