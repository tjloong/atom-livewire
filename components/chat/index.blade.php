@php
$readonly = $attributes->get('readonly', false);
$formatting = $attributes->get('formatting', true);
$placeholder = $attributes->get('placeholder', 'write-comment');

$upload = $attributes->get('upload');
$upload = $upload === true || is_array($upload) ? [
    'max' => config('atom.max_upload_size'),
    'accept' => '*',
    'multiple' => true,
    ...(is_array($upload) ? $upload : []),
] : false;

$attrs = $attributes
    ->class(['p-5 overflow-auto space-y-5'])
    ->except('readonly');
@endphp

<div
    x-cloak
    x-data="chat({ upload: {{ js($upload) }} })"
    x-init="$nextTick(() => scroll())"
    x-on:drop.prevent="drop($event)"
    x-on:paste.stop="paste($event)"
    x-on:editor-enter="submit()"
    x-on:scroll-chat="$nextTick(() => scroll())"
    data-atom-chat
    {{ $attrs->except('class') }}>
    @if ($slot->isEmpty())
        <atom:empty size="sm"/>
    @else
        <div x-ref="conversation" {{ $attrs->only('class') }}>
            {{ $slot }}
        </div>
    @endif

    @if (!$readonly)
        <div
            wire:ignore
            x-init="$nextTick(() => createEditor({ placeholder: {{ js(t($placeholder)) }} }))"
            class="p-3 border-t border-zinc-200">
            <div class="editor editor-transparent">
                @isset($mention)
                    <atom:_editor.mention
                        :options="$mention->attributes->get('options', [])"
                        :filters="$mention->attributes->get('filters', [])">
                        {{ $mention }}
                    </atom:_editor.mention>
                @endisset

                <div class="editor-container bg-white border border-zinc-200 shadow-sm rounded-lg">
                    <div class="flex items-end min-h-10">
                        <div x-ref="editor" class="grow px-3"></div>

                        <div class="shrink-0 p-2">
                            <template x-if="upload.uploading" hidden>
                                <div class="bg-black rounded-md shadow flex items-center gap-3 py-1.5 px-3">
                                    <div class="shrink-0 text-theme">
                                        <atom:icon loading/>
                                    </div>

                                    <div class="font-medium text-zinc-100 text-sm">
                                        @t('uploading') <span x-text="upload.progress"></span></span>
                                    </div>
                                </div>
                            </template>

                            <template x-if="!upload.uploading" hidden>
                                <div class="flex items-center divide-x border border-zinc-200 rounded-md shadow-sm">
                                    @if ($formatting)
                                        <atom:chat.formatting/>
                                    @endif

                                    @if ($upload)
                                        <atom:chat.upload :settings="$upload"/>
                                    @endif

                                    <button
                                        type="button"
                                        x-tooltip="{{ js(t('submit')) }}"
                                        x-on:click="submit()"
                                        class="p-1.5 flex items-center justify-center">
                                        <atom:icon line-break size="15"/>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>

                    <template x-if="upload.files.length">
                        <div class="py-2 px-3 flex items-center gap-3 flex-wrap">
                            <template x-for="(file, i) in upload.files" hidden>
                                <div class="group shrink-0 w-14 space-y-2">
                                    <figure class="relative w-14 h-14 bg-zinc-200 rounded-md overflow-hidden border border-zinc-300 flex items-center justify-center">
                                        <template x-if="file.src">
                                            <img x-bind:src="file.src" class="w-full h-full object-cover">
                                        </template>

                                        <template x-if="!file.src">
                                            <atom:icon file/>
                                        </template>

                                        <template x-if="!upload.uploading">
                                            <div
                                                x-on:click="upload.files.splice(i, 1)"
                                                class="absolute inset-0 bg-black/50 cursor-pointer items-center justify-center text-white hidden group-hover:flex">
                                                <atom:icon delete/>
                                            </div>
                                        </template>
                                    </figure>

                                    <div class="grid text-center">
                                        <div x-text="file.name" class="text-xs text-muted-more truncate"></div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    @endif
</div>
