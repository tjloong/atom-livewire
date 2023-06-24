@if ($errors->any())
    <div {{ $attributes }}>
        <div class="p-4 rounded-md bg-red-100 border-red-300">
            <div class="grid gap-1">
                @foreach ($errors->all() as $error)
                    <div class="flex items-center gap-2">
                        <x-icon 
                            name="circle-xmark" 
                            class="text-red-400 shrink-0" 
                            size="20"
                        />
                        <div class="text-red-600 font-medium">
                            {{ __($error) }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
