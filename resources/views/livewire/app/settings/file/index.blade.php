<div class="max-w-screen-xl">
    <x-page-header title="Files and Media"/>

    <x-table>
        <x-slot:header>
            <x-table.searchbar :total="$this->paginator->total()"/>
            
            @if ($count = count($checkboxes))
                <x-table.toolbar>
                    <x-table.checkboxes :count="$count"/>
                    <x-button.delete inverted
                        :label="'Delete ('.$count.')'"
                        title="Delete Files"
                        message="Are you sure to DELETE the selected files?"
                    />
                </x-table.toolbar>
            @else
                <x-table.toolbar class="w-full">
                    <div 
                        x-data="{
                            url: false,
                            uploading: false,
                            upload (files) {
                                document.querySelector('#file-uploader').dispatchEvent(
                                    new CustomEvent('upload', { bubble: false, detail: files })
                                )
                            },
                        }"
                        x-on:uploading="uploading = true"
                        x-on:uploaded="uploading = false; $wire.emit('refresh')"
                    >
                        <div x-show="!uploading && !url" class="p-4 bg-white flex items-center gap-3 justify-between">
                            <x-form.select :label="false"
                                wire:model="filters.mime"
                                :options="enum('file.type')->map(fn($type) => [
                                    'value' => $type->mime(),
                                    'label' => $type->label(),
                                ])->toArray()"
                                placeholder="All Types"
                            />
            
                            <div class="flex items-center gap-2">
                                <input x-ref="file" 
                                    x-on:change="upload($event.target.files)" 
                                    x-on:input.stop
                                    type="file" 
                                    class="hidden" 
                                    multiple
                                >
                
                                <x-button label="Upload" x-on:click="$refs.file.click()" icon="upload" outlined/>
                                <x-button label="From URL" x-on:click="url = true" icon="code" outlined/>
                            </div>
                        </div>

                        <div class="bg-slate-100">
                            <x-form.file.uploader/>
                        </div>

                        <div x-show="url" x-on:input.stop="url = false; $wire.emit('refresh')" class="p-4">
                            <div class="flex justify-end">
                                <x-close x-on:click="url = false"/>
                            </div>
                            <x-form.file.url/>
                        </div>
                    </div>

                </x-table.toolbar>
            @endif
        </x-slot:header>

        <x-slot:thead>
            <x-table.th checkbox/>
            <x-table.th label="File Name" sort="name"/>
            <x-table.th label="Size" sort="size"/>
            <x-table.th label="Last Updated" sort="updated_at"/>
        </x-slot:thead>

        @foreach ($this->paginator->items() as $file)
            <x-table.tr>
                <x-table.td :checkbox="$file->id"/>
                <x-table.td>
                    <div class="flex items-center gap-3">
                        <x-thumbnail :file="$file" size="30"/>
                        <div class="grow">
                            <div wire:click="$emitTo('app.settings.file.form', 'open', @js($file->id))" class="cursor-pointer font-medium">
                                {{ str($file->name)->limit(50) }}
                            </div>
                            <div class="text-sm font-medium text-gray-500">
                                {{ $file->mime }}
                            </div>
                        </div>
                    </div>
                </x-table.td>
                <x-table.td :label="$file->size" class="text-right"/>
                <x-table.td :date="$file->updated_at" class="text-right"/>
            </x-table.tr>
        @endforeach
    </x-table>

    {!! $this->paginator->links() !!}

    @livewire('app.settings.file.form')
</div>
