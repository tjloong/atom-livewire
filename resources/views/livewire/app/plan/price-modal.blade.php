<x-form modal id="price-modal" header="Plan Price">
    @if ($price)
        <x-form.group cols="2">
            <x-form.text wire:model.defer="price.code" label="Price Code"/>
            <x-form.text wire:model.defer="price.description" caption="This will appear in the user receipt."/>
            <x-form.number wire:model.defer="price.amount" step=".01" :prefix="$price->plan->currency"/>
        </x-form.group>

        <x-form.group cols="2">
            <x-form.number wire:model.defer="price.valid.count" label="Valid Period Count"/>
            <x-form.select wire:model="price.valid.interval" label="Valid Period Interval" :options="collect([
                'day',
                'week',
                'month',
                'year',
            ])->map(fn($val) => ['value' => $val, 'label' => str()->headline($val)])"/>
        </x-form.group>

        <x-form.group>
            <div>
                <x-form.checkbox wire:model="price.is_recurring" label="Recurring"/>
                <x-form.checkbox wire:model="price.is_active" label="Active"/>
            </div>
        </x-form.group>

        <x-slot:foot>
            <div class="flex items-center gap-2">
                <x-button.submit/>
                
                @if ($price->exists)
                    <x-button.delete inverted
                        title="Delete Plan Price"
                        message="Are you sure to DELETE this price?"
                    />
                @endif
            </div>
        </x-slot:foot>
    @endif
</x-form>