<div class="max-w-screen-md mx-auto">
    <x-page-header :title="$page->name" back/>
    @livewire($component, ['page' => $page])
</div>