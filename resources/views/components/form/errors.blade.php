@if ($errors->any())
    <div class="bg-red-50 rounded-lg p-4 grid gap-2 mt-6">
        @foreach ($errors->all() as $err)
            <div class="flex gap-2">
                <x-icon name="x-circle" size="xs" class="text-red-400"/>
                <div class="self-center text-red-600 font-medium">
                    {{ __($err) }}
                </div>
            </div>
        @endforeach
    </div>
@endif