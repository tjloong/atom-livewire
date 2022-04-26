@if ($provider = $attributes->get('provider'))
    <form 
        x-data="paymentGateway(@js([
            'value' => $value,
            'callback' => $attributes->get('callback'),
            'signUrl' => route('__'.$provider.'.sign'),
            'provider' => $provider,
        ]))"
        x-on:submit.prevent="submit" 
        class="grid gap-6 p-5"
    >
        @csrf

        @if ($provider === 'ozopay')
            <div class="grid gap-4">
                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <x-input.field x-bind:class="{ error: errors.first_name }" required>
                            <x-slot name="label">{{ __('First Name') }}</x-slot>
                            <input type="text" x-model="value.first_name" class="form-input w-full">
                            <div x-show="errors.first_name" class="text-sm text-red-500 font-medium mt-1">{{ __('First name is required.') }}</div>
                        </x-input.field>
                    </div>
        
                    <div>
                        <x-input.field x-bind:class="{ error: errors.last_name }" required>
                            <x-slot name="label">{{ __('Last Name') }}</x-slot>
                            <input type="text" x-model="value.last_name" class="form-input w-full">
                            <div x-show="errors.last_name" class="text-sm text-red-500 font-medium mt-1">{{ __('Last name is required.') }}</div>
                        </x-input.field>
                    </div>
                </div>
        
                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <x-input.field x-bind:class="{ error: errors.phone }" required>
                            <x-slot name="label">{{ __('Phone') }}</x-slot>
                            <input type="text" x-model="value.phone" class="form-input w-full">                   
                            <div x-show="errors.phone" class="text-sm text-red-500 font-medium mt-1">{{ __('Phone is required.') }}</div>
                        </x-input.field>
                    </div>
        
                    <div>
                        <x-input.field x-bind:class="{ error: errors.email }" required>
                            <x-slot name="label">{{ __('Email') }}</x-slot>
                            <input type="email" x-model="value.email" class="form-input w-full">    
                            <div x-show="errors.email" class="text-sm text-red-500 font-medium mt-1">{{ __('Email is required.') }}</div>
                        </x-input.field>
                    </div>
                </div>
        
                <div>
                    <x-input.field x-bind:class="{ error: errors.address }" required>
                        <x-slot name="label">{{ __('Billing Address') }}</x-slot>
                        <input type="text" x-model="value.address" class="form-input w-full">
                        <div x-show="errors.address" class="text-sm text-red-500 font-medium mt-1">{{ __('Address is required.') }}</div>
                    </x-input.field>
                </div>
        
                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <x-input.field x-bind:class="{ error: errors.city }" required>
                            <x-slot name="label">{{ __('City') }}</x-slot>
                            <input type="text" x-model="value.city" class="form-input w-full">
                            <div x-show="errors.city" class="text-sm text-red-500 font-medium mt-1">{{ __('City is required.') }}</div>
                        </x-input.field>
                    </div>
        
                    <div>
                        <x-input.field x-bind:class="{ error: errors.postcode }" required>
                            <x-slot name="label">{{ __('Postcode') }}</x-slot>
                            <input type="text" x-model="value.postcode" class="form-input w-full">
                            <div x-show="errors.postcode" class="text-sm text-red-500 font-medium mt-1">{{ __('Postcode is required.') }}</div>
                        </x-input.field>
                    </div>
                </div>
        
                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <x-input.field x-bind:class="{ error: errors.country }" required>
                            <x-slot name="label">{{ __('Country') }}</x-slot>
                            <select x-model="value.country" class="form-input w-full">
                                <option value="">-- {{ __('Select Country') }} --</option>
                                @foreach (metadata()->countries() as $country)
                                    <option value="{{ $country->iso_code }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                            <div x-show="errors.country" class="text-sm text-red-500 font-medium mt-1">{{ __('Country is required.') }}</div>
                        </x-input.field>
                    </div>
        
                    <div x-show="value.country === 'MY'">
                        <x-input.field x-bind:class="{ error: errors.state }" required>
                            <x-slot name="label">{{ __('State') }}</x-slot>
                            <select x-model="value.state" class="form-input w-full">
                                <option value="">-- {{ __('Select State') }} --</option>
                                @foreach (metadata()->states('MY') as $state)
                                    <option value="{{ $state->name }}">{{ $state->name }}</option>
                                @endforeach
                            </select>
                            <div x-show="errors.state" class="text-sm text-red-500 font-medium mt-1">{{ __('State is required.') }}</div>
                        </x-input.field>
                    </div>
        
                    <div x-show="value.country !== 'MY'">
                        <x-input.field required>
                            <x-slot name="label">{{ __('State') }}</x-slot>
                            <input type="text" x-model="value.state" class="form-input w-full">
                        </x-input.field>
                    </div>
                </div>
            </div>

        @elseif ($provider === 'ipay')
            <div>
                <x-input.field x-bind:class="{ error: errors.name }" required>
                    <x-slot name="label">{{ __('Customer Name') }}</x-slot>
                    <input type="text" x-model="value.name" class="form-input w-full">
                    <div x-show="errors.name" class="text-sm text-red-500 font-medium mt-1">{{ __('Customer name is required.') }}</div>
                </x-input.field>
        
                <x-input.field x-bind:class="{ error: errors.email }" required>
                    <x-slot name="label">{{ __('Email') }}</x-slot>
                    <input type="email" x-model="value.email" class="form-input w-full">    
                    <div x-show="errors.email" class="text-sm text-red-500 font-medium mt-1">{{ __('Customer email is required.') }}</div>
                </x-input.field>
        
                <x-input.field x-bind:class="{ error: errors.phone }" required>
                    <x-slot name="label">{{ __('Phone') }}</x-slot>
                    <input type="text" x-model="value.phone" class="form-input w-full">                   
                    <div x-show="errors.phone" class="text-sm text-red-500 font-medium mt-1">{{ __('Customer contact number is required.') }}</div>
                </x-input.field>
            </div>

        @endif

        @if ($slot->isNotEmpty())
            <div>{{ $slot }}</div>
        @endif

        <x-button size="md" color="green" type="submit" block x-bind:disabled="loading">
            <span x-text="loading ? '{{ __('Loading') }}' : '{{ __('Continue') }}'"></span>
        </x-button>
    </form>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('paymentGateway', (config) => ({
                value: config.value,
                callback: config.callback,
                signUrl: config.signUrl,
                provider: config.provider,
                loading: false,
                errors: {},

                validated () {
                    this.errors = {}

                    let fields = []

                    if (this.provider === 'ozopay') {
                        fields = ['first_name', 'last_name', 'phone', 'email', 'address', 'city', 'postcode', 'country', 'state']
                    }
                    else if (this.provider === 'ipay') {
                        fields = ['name', 'email', 'phone']
                    }
                        
                    fields.forEach(field => this.errors[field] = !Boolean(this.value[field]))

                    return Object.values(this.errors).filter(err => (err === true)).length <= 0
                },

                submit () {
                    if (!this.validated()) return

                    this.loading = true

                    if (this.callback) {
                        this.$wire.call(this.callback, { ...this.value, provider: this.provider })
                            .then(res => this.value = { ...this.value, ...res })
                            .then(() => this.sign())
                            .catch(() => this.loading = false)
                    }
                    else this.sign().catch(() => this.loading = false)
                },

                sign () {
                    const data = {
                        method: 'post',
                        body: JSON.stringify({ params: this.value }),
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            "X-CSRF-Token": this.$el.querySelector('input[name=_token]').value
                        },
                    }

                    return fetch(this.signUrl, data)
                        .then(res => res.json())
                        .then(params => this.redirect(params))
                },

                redirect (params) {
                    const method = params.endpoint_method || 'post'

                    if (method === 'post') {
                        const form = document.createElement('form')
                        form.method = 'POST'
                        form.action = params.endpoint
    
                        Object.keys(params.body || {}).forEach(key => {
                            const input = document.createElement('input')
                            input.setAttribute('name', key)
                            input.setAttribute('value', params.body[key] || '')
                            form.appendChild(input)
                        })

                        document.body.appendChild(form)
                        form.submit()
                    }
                    else {
                        const url = params.endpoint
                        const qs = new URLSearchParams(params.body || {}).toString()
                        const endpoint = url + (qs.length ? `?${qs}` : '')

                        window.location = endpoint
                    }
                },
            }))
        })
    </script>

@else
    <div class="grid divide-y">
    @foreach ($providers as $provider)
        <div x-data="{ show: false }" x-on:click.away="show = false">
            <div x-on:click="show = !show" class="flex justify-between gap-4 py-3 px-4 cursor-pointer">
                @isset($$provider)
                    {{ $$provider }}
                @else
                    <div class="flex items-center gap-4">
                        <div class="font-semibold">
                            {{ $titles[$provider] ?? str($provider)->headline() }}
                        </div>
    
                        <div class="flex items-center gap-2">
                            @foreach ($logos[$provider] as $logo)
                                <x-logo :name="$logo" class="h-5 max-w-[60px]"/>
                            @endforeach
                        </div>
                    </div>
                @endif
    
                <x-icon name="chevron-right"/>
            </div>
    
            <div x-show="show" x-transition>
                <x-payment-gateway 
                    :provider="$provider" 
                    :value="$value" 
                    :callback="$attributes->get('callback')"
                >
                    {{ $slot }}
                </x-payment-gateway>
            </div>
        </div>
    @endforeach
    </div>

@endif

