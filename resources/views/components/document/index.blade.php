<x-box>
    <div class="flex flex-col divide-y">
        <div class="grid md:grid-cols-2">
            <div class="p-4 bg-slate-100 flex flex-col gap-4">
                @isset($contact)
                    {{ $contact }}
                @elseif ($contact = $attributes->get('contact'))
                    @foreach ($contact as $key => $val)
                        <x-form.field :label="$key" :value="is_string($val) ? $val : null">
                            @if (is_array($val))
                                <div class="flex gap-4">
                                    @if ($image = data_get($val, 'image'))
                                        <div class="shrink-0">
                                            <x-thumbnail :url="$image" size="45"/>
                                        </div>
                                    @elseif ($avatar = data_get($val, 'avatar'))
                                        <div class="shrink-0">
                                            <x-thumbnail :url="$avatar" size="45"/>
                                        </div>
                                    @endif

                                    <div class="grow">
                                        @if ($address = data_get($val, 'address'))
                                            <div class="flex flex-col">
                                                @if ($name = data_get($val, 'name')) 
                                                    <div class="font-medium">{{ $name }}</div>
                                                @endif
        
                                                @if ($company = data_get($val, 'company')) 
                                                    <div class="font-medium">{{ $company }}</div>
                                                @endif
        
                                                <div>{{ $address }}</div>
                                            </div>
                                        @elseif ($href = data_get($val, 'href'))
                                            <x-link :label="data_get($val, 'value')" 
                                                :href="$href" 
                                                :target="data_get($val, 'target', '_blank')"
                                            />
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </x-form.field>
                    @endforeach
                @endif
            </div>

            <div class="flex flex-col divide-y">
                @isset($info)
                    {{ $info }}
                @elseif ($info = $attributes->get('info'))
                    @foreach ($info as $key => $val)
                        <x-field :label="$key"
                            :value="is_string($val) ? $val : data_get($val, 'value')"
                            :badge="data_get($val, 'status')"
                            :href="data_get($val, 'href')"
                            :tags="data_get($val, 'tags')"
                            :small="data_get($val, 'small')"
                        />
                    @endforeach
                @endif
            </div>
        </div>

        @if ($slot->isNotEmpty())
            {{ $slot }}
        @else
            <div class="flex flex-col divide-y">
                <x-document.item-header :columns="$attributes->get('columns')"/>

                @foreach ($attributes->get('items', []) as $item)
                    <x-document.item 
                        :image="optional($item->image)->url"
                        :name="$item->name"
                        :variant="$item->variant_name"
                        :description="$item->description"
                        :qty="$item->qty"
                        :amount="$item->amount"
                        :total="$item->subtotal"
                    />
                @endforeach

                @if ($total = $attributes->get('total'))
                    <div class="p-1">
                        <div class="rounded-lg p-3 bg-slate-100 md:w-1/2 md:ml-auto">
                            <x-document.total :total="$total"/>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>
</x-box>