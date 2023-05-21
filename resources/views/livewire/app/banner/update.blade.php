<div class="max-w-screen-md mx-auto">
    <x-page-header :title="$banner->name" back>
        <x-button.delete inverted
            title="Delete Banner"
            message="Are you sure to DELETE this banner?"
        />
    </x-page-header>

    @livewire(lw('app.banner.form'), compact('banner'))
</div>