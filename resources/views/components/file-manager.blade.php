@php
$title = $title ?? $attributes->getAny('title', 'heading');
$accept = $attributes->get('accept');
$readonly = $attributes->get('readonly');
$wire = $attributes->wire('model')->value();
$files = collect();

if ($values = (array) get($this, $wire)) {
    $files = model('file')->whereIn('id', $values)->latest()->take(1000)->get();
    $files = collect($values)->map(fn($id) => $files->firstWhere('id', $id));
}
@endphp

<div
    x-cloak
    x-data="{
        value: @entangle($attributes->wire('model')),
        readonly: @js($readonly),
        checkboxes: [],

        select (id) {
            if (this.readonly) return
            this.checkboxes.toggle(id)
        },

        remove () {
            this.value = this.value.filter(val => !this.checkboxes.includes(val))
            this.checkboxes = []
        },

        sort (id) {
            this.value = id.map(val => (+val))
        },
    }"
    x-on:uploaded="$event.detail.forEach(file => value.prepend(file.id))"
    class="relative">
    <x-box>
        @if ($title instanceof \Illuminate\View\ComponentSlot)
            <x-slot:heading
                :title="$title->attributes->get('title')"
                :subtitle="$title->attributes->get('subtitle')"
                :status="$title->attributes->get('status')">
                {{ $title }}
            </x-slot:heading>
        @else
            <x-slot:heading :title="$title ?? 'app.label.file'">
                <x-button action="file-upload" :accept="$accept" multiple/>
            </x-slot:heading>
        @endif

        <template x-if="checkboxes.length">
            <div class="border-y bg-gray-100 py-2 px-5 flex items-center gap-5 flex-wrap">
                <div class="shrink-0 flex items-center gap-3">
                    <div class="flex items-center gap-2">
                        <x-icon name="check-double" class="text-gray-400 text-sm"/>
                        <div x-text="`${checkboxes.length}/${value.length}`" class="font-medium text-gray-500"></div>
                    </div>

                    <template x-if="checkboxes.length < value.length">
                        <x-anchor label="app.label.all" class="text-sm" x-on:click="checkboxes = [...value]"/>
                    </template>

                    <x-anchor label="app.label.none" class="text-sm" x-on:click="checkboxes = []"/>
                </div>

                <div class="shrink-0 flex items-center gap-2">
                    <x-button action="remove" invert sm x-prompt.delete="{
                        title: 'app.label.remove-selected-records',
                        message: 'app.label.are-you-sure-to-remove-selected-records',
                        confirm: () => remove(),
                    }"/>
                </div>
            </div>
        </template>

        <div x-sort="sort($sortid)" class="p-5 grid gap-4 grid-cols-6">
            @forelse ($files as $file)
                <div
                    data-sortid="@js($file->id)"
                    x-sort:item
                    x-on:click="() => {
                        if (checkboxes.length) select(@js($file->id))
                        else $wire.emit('editFile', @js($file->id))
                    }"
                    class="relative cursor-pointer">           
                    <x-file :file="$file" lg no-label/>

                    @if (!$readonly)                    
                        <div x-show="checkboxes.includes(@js($file->id))" class="absolute inset-0 bg-black/30 rounded-lg"></div>
                        <div
                            data-checkbox
                            x-on:click.stop="select(@js($file->id))"
                            x-bind:class="checkboxes.includes(@js($file->id)) ? 'active border-theme bg-theme text-white' : 'text-gray-400 bg-white'"
                            class="absolute top-2 left-2 w-5 h-5 rounded border flex items-center justify-center">
                            <x-icon name="check" class="text-sm"/>
                        </div>
                    @endif
                </div>
            @empty
                <div class="col-span-6">
                    <x-no-result xs/>
                </div>
            @endforelse
        </div>
    </x-box>
</div>