<div class="max-w-screen-xl mx-auto">
    <x-page-header :title="$page->title" back/>

    <div class="grid gap-6 md:grid-cols-12">
        <div class="md:col-span-3">
            <x-sidenav wire:model="tab">
                <x-sidenav.item name="content">Page Content</x-sidenav.item>
                <x-sidenav.item>SEO</x-sidenav.item>
            </x-sidenav>
        </div>
    
        <div class="md:col-span-9">
            <div>
                @if ($tab === 'content')
                    @livewire('app.page.form.content', ['page' => $page], key($tab))
                @endif
            </div>

            <div>
                @if ($tab === 'seo')
                    @livewire('app.page.form.seo', ['page' => $page], key($tab))
                @endif
            </div>
        </div>
    </div>
</div>