@php
$label = $attributes->get('label');
$caption = $attributes->get('caption');
$country = $attributes->get('country');
$field = $attributes->get('field') ?? $attributes->wire('model')->value();
$required = $attributes->get('required') ?? $this->form['required'][$field] ?? false;
$error = $attributes->get('error') ?? $this->errors[$field] ?? null;
@endphp

@if ($label || $caption)
    <atom:_input.field
        :label="$label"
        :caption="$caption"
        :required="$required"
        :error="$error">
        <atom:addresses :attributes="$attributes->except(['label', 'caption', 'error'])"/>
    </atom:_input.field>
@else
    <div
        x-data="addresses({
            values: @entangle($attributes->wire('model')),
            country: @js($country),
        })"
        x-on:submit-address="submit($event.detail)">
        <div class="space-y-3">
            <atom:list>
                <template x-for="value in values" hidden>
                    <atom:list.item x-on:remove="remove(value)">
                        <div
                            x-text="getString(value)"
                            x-on:click.stop="Atom.modal('address-form').show(value)"
                            class="cursor-pointer">
                        </div>
                    </atom:list.item>
                </template>
            </atom:list>

            <atom:_button icon="add" size="sm" variant="ghost" x-on:click="Atom.modal('address-form').show()">
                @t('add-address')
            </atom:_button>
        </div>

        <atom:modal name="address-form" x-on:open="$el.querySelector('[data-atom-address-form]').dispatch('open-address-form', $event.detail, false)">
            <div
                x-data="{ address: null }"
                x-on:open-address-form="initAddress($event.detail)"
                data-atom-address-form>
                <template x-if="address" hidden>
                    <atom:_form>
                        <atom:_heading size="xl">
                            <div x-show="address.id">@t('edit-address')</div>
                            <div x-show="!address.id">@t('add-address')</div>
                        </atom:_heading>

                        <atom:_input x-model="address.line_1" label="address-line-1"/>
                        <atom:_input x-model="address.line_2" label="address-line-2"/>
                        <atom:_input x-model="address.line_3" label="address-line-3"/>

                        <div class="grid gap-6 md:grid-cols-2">
                            <atom:_input x-model="address.postcode" label="postcode"/>
                            <atom:_input x-model="address.city" label="city"/>

                            <atom:_select x-model="address.country" options="countries" label="country"
                                :wire:key="$attributes->wire('model')->value().'-country'">
                            </atom:_select>

                            <template x-if="address.country === 'MALAYSIA'" hidden>
                                <atom:_select x-model="address.state" options="states" label="state"
                                    :wire:key="$attributes->wire('model')->value().'-state'">
                                </atom:_select>
                            </template>

                            <template x-if="address.country !== 'MALAYSIA'" hidden>
                                <atom:_input x-model="address.state" label="state"/>
                            </template>
                        </div>

                        <atom:_input x-model="address.notes" label="notes"/>

                        <atom:_button icon="check" variant="primary" x-on:click="() => {
                            $dispatch('submit-address', address)
                            $dispatch('modal-close', { name: 'address-form' })
                        }">
                            @t('submit')
                        </atom:_button>
                    </atom:_form>
                </template>
            </div>
        </atom:modal>
    </div>
@endif
