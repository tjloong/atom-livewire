@if (!enabled_module('roles') || tier('root') || !empty(tenant()))
    <template></template>
@else
    <div>
        @json($this->roles)
    </div>
@endif