@props([
    'fields' => $attributes->get('fields'),
    'errors' => $attributes->get('errors'),
])

<form 
    class="{{ $attributes->get('class', 'bg-white shadow border rounded-xl') }}"
    wire:submit.prevent="{{ $attributes->get('submit', 'submit') }}"
>
    @if ($header = $attributes->get('header'))
        <div class="m-1 py-3 px-4 text-lg font-bold border-b">
            {{ __($header) }}
        </div>
    @elseif (isset($header))
        <div {{ $header->attributes->class(['mx-1']) }}>
            {{ $header }}
        </div>
    @endif

    @if ($fields)
        <div class="flex flex-col divide-y">
            @foreach (array_filter($fields) as $section)
                <div class="m-1 p-5 flex flex-col gap-4">
                    @if ($title = data_get($section, '__title'))
                        <div class="text-lg font-medium">{{ $title }}</div>
                    @endif

                    <div class="{{ data_get($section, '__class', 'grid gap-6 md:grid-cols-2') }}">
                        @foreach ($section as $field => $prop)
                            @if ($field === 'slot')
                                {{ $$prop }}
                            @else
                                @php $label = data_get($prop, 'label') @endphp
                                @php $input = data_get($prop, 'input') @endphp
                                @php $error = optional($errors)->first($field) @endphp
                                @php $required = data_get($prop, 'rules.required') @endphp
                                @php $inputattr = data_get($prop, 'attr') ?? data_get($prop, 'attributes') @endphp
                                @php $fieldslot = data_get($prop, 'slot') @endphp
    
                                @if ($ro = data_get($prop, 'readonly'))
                                    <x-form.field :label="$label" :error="$error" :required="$required">
                                        {{ $ro }}
                                    </x-form.field>
                                @elseif (is_array($input))
                                    <x-form.select :label="$label"
                                        wire:model="{{ $field }}"
                                        :options="$input"
                                        :error="$error"
                                        :required="$required"
                                    />
                                @elseif ($input === 'text')
                                    <x-form.text :label="$label"
                                        wire:model.defer="{{ $field }}"
                                        :error="$error"
                                        :required="$required"
                                    />
                                @elseif ($input === 'select.state')
                                    @if ($country = data_get($inputattr, 'country'))
                                        <x-form.select.state :label="$label"
                                            :country="data_get($inputattr, 'country')"
                                            wire:model="{{ $field }}"
                                            :error="$error"
                                            :required="$required"
                                            :uid="data_get($inputattr, 'uid')"
                                        />
                                    @endif
                                @elseif (str($input)->is('select.*'))
                                    <x-dynamic-component :component="'form.'.$input" :label="$label"
                                        wire:model="{{ $field }}"
                                        :error="$error"
                                        :required="$required"
                                        :uid="data_get($inputattr, 'uid')"
                                    />
                                @elseif ($input === 'file')
                                    <x-form.field :label="$label" :error="$error" :required="$required">
                                        @if ($tn = data_get($prop, 'thumbnail'))
                                            <x-thumbnail :file="$tn" wire:remove="$set('{{ $field }}', null)"/>
                                        @else
                                            <x-form.file wire:model="{{ $field }}"
                                                :accept="data_get($inputattr, 'accept', '*')"
                                                :multiple="data_get($inputattr, 'multiple', false)"
                                                :visibility="data_get($inputattr, 'visibility', 'public')"
                                                :library="data_get($inputattr, 'library')"
                                                :web-image="data_get($inputattr, 'web-image')"
                                                :youtube="data_get($inputattr, 'youtube')"
                                            />
                                        @endif
                                    </x-form.field>
                                @elseif ($input === 'checkbox')
                                    <x-form.checkbox wire:model="{{ $field }}" :label="$label"/>
                                @elseif (in_array($input, ['text', 'textarea', 'number', 'amount', 'phone']))
                                    <x-dynamic-component :component="'form.'.$input" :label="$label"
                                        wire:model.defer="{{ $field }}"
                                        :error="$error"
                                        :required="$required"
                                    />
                                @elseif (in_array($input, ['date']))
                                    <x-dynamic-component :component="'form.'.$input" :label="$label"
                                        wire:model="{{ $field }}"
                                        :error="$error"
                                        :required="$required"
                                    />
                                @elseif ($fieldslot)
                                    {{ $$fieldslot }}
                                @endif
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        {{ $slot }}
    @else
        <div class="m-1 p-5 grid gap-6">
            {{ $slot }}
        </div>
    @endif

    @isset($errorAlert)
        <div class="p-4">{{ $errorAlert }}</div>
    @elseif ($errors && $errors->any())
        <div class="p-4"><x-alert :errors="$errors"/></div>
    @endisset

    @isset($foot)
        <div class="py-4 px-6 bg-gray-100 rounded-b-lg">
            {{ $foot }}
        </div>
    @else
        <div class="py-4 px-6 bg-gray-100 rounded-b-lg">
            <x-button.submit/>
        </div>
    @endisset
</form>
