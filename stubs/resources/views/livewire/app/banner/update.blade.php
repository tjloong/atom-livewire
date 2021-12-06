<div class="max-w-md mx-auto">
    <x-page-header :title="$banner->name" back/>
    @livewire('app.banner.form', ['banner' => $banner])
</div>