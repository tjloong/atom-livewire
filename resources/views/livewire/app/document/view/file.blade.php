<div>
    <x-box header="Attachments">
        <div class="flex flex-col divide-y">
            @if ($this->files->count())
                <div class="max-h-[150px] overflow-auto">
                    <div class="flex flex-col divide-y">
                        @foreach ($this->files as $file)
                            <div
                                x-data="{ hover: false }"
                                x-on:mouseover="hover = true"
                                x-on:mouseout="hover = false"
                                class="py-2 px-4 flex items-center gap-3 hover:bg-slate-100" 
                            >
                                <x-icon :name="$file->icon" class="shrink-0 text-gray-500"/>
        
                                <div class="grow grid">
                                    <a class="truncate" href="{{ $file->url }}" target="_blank">
                                        {{ $file->name }}
                                    </a>
                                </div>
        
                                <div x-show="!hover" class="shrink-0">
                                    <x-badge :label="$file->type" size="xs"/>
                                </div>
                                
                                <div x-show="hover" class="shrink-0">
                                    <x-close.delete
                                        title="Remove Attachment"
                                        message="Are you sure to remove this attachment?"
                                        callback="detach"
                                        :params="$file->id"
                                    />
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="p-4">
                <x-form.file wire:file="attach" multiple/>
            </div>
        </div>
    </x-box>
</div>
