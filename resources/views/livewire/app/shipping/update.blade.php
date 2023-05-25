<div class="max-w-screen-md mx-auto">
    <x-page-header :title="$rate->name" back="auto"/>
    @livewire(lw('app.shipping.form'), compact('rate'))
</div>