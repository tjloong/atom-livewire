<div class="bg-slate-100 p-4 grid gap-4">
    <x-form.field label="Contact">
        <div class="grid">
            @if (Route::has('contact.view') && auth()->user()->can('contact.view'))
                <a href="{{ route('app.contact.view', [$document->contact_id]) }}">
                    {{ $document->name }}
                </a>
            @else
                <div class="font-semibold">
                    {{ $document->name }}
                </div>
            @endif
            
            <div class="text-sm text-gray-500 font-medium">
                {{ $document->address }}
            </div>
        </div>
    </x-form.field>
    
    @if ($person = $document->person)
        <x-form.field label="Attention To">
            {{ $person }}
        </x-form.field>
    @endif
</div>
