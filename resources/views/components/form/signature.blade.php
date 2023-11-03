@php
    $wire = $attributes->wire('model')->value();
    $width = $attributes->get('width', 300);
    $height = $attributes->get('height', 300);
    $readonly = $attributes->get('readonly', false);
@endphp

<x-form.field {{ $attributes }}>
    <div x-cloak
        x-data="{
            value: @if ($wire) @entangle($wire) @endif,
            readonly: @js($readonly),
            signaturePad: null,
            loadSignaturePad () {
                this.signaturePad = new SignaturePad(this.$refs.canvas)

                if (screensize('sm')) {
                    this.$nextTick(() => {
                        window.onresize = () => this.resizeCanvas()
                        this.resizeCanvas()
                    })
                }
            },
            loadSignature () {
                if (!this.value) return
                if (!this.signaturePad) return

                this.signaturePad.fromDataURL(this.value, {
                    ratio: 1,
                    width: @js($width),
                    height: @js($height), 
                })
            },
            resizeCanvas() {
                const ratio =  Math.max(window.devicePixelRatio || 1, 1);
                this.$refs.canvas.width = this.$refs.canvas.offsetWidth * ratio;
                this.$refs.canvas.height = this.$refs.canvas.offsetHeight * ratio;
                this.$refs.canvas.getContext('2d').scale(ratio, ratio);

                // this.signaturePad.clear();
                this.signaturePad.fromData(this.signaturePad.toData());
            },
            save () {
                if (this.signaturePad.isEmpty()) return
                this.value = this.signaturePad.toDataURL()
            },
            reset () {
                this.signaturePad.clear()
                this.value = null
            },
        }"
        x-init="() => {
            loadSignaturePad()
            loadSignature()
            $watch('value', () => loadSignature())
            if (readonly) signaturePad.off()
        }"
        class="flex items-center justify-center border rounded-md bg-gray-100 p-3 relative">
        <div class="inline-flex flex-col gap-2">
            <div class="flex flex-col divide-y bg-white border rounded-lg shadow overflow-hidden">
                <div class="p-2">
                    <div class="border rounded border-dashed border-gray-300">
                        <canvas x-ref="canvas"
                            width="{{ $width }}" 
                            height="{{ $height }}"></canvas>
                    </div>
                </div>

                @if (!$readonly)
                    <div class="flex items-center divide-x text-sm">
                        <button type="button" class="grow hover:bg-slate-50 py-2 px-4 flex items-center justify-center gap-3"
                            x-on:click="save()">
                            <x-icon name="check" class="text-green-500"/> {{ tr('common.label.done') }}
                        </button>
        
                        <button type="button" class="grow hover:bg-slate-50 py-2 px-4 flex items-center justify-center gap-3"
                            x-on:click="reset()">
                            <x-icon name="xmark" class="text-red-500"/> {{ tr('common.label.reset') }}
                        </button>
                    </div>
                @endif
            </div>

            @if ($slot->isNotEmpty())
                {{ $slot }}
            @endif
        </div>
    </div>
</x-form.field>