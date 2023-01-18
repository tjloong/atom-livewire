<div class="max-w-screen-xl mx-auto">
    <x-page-header :title="$this->title" back>
        @livewire(lw('app.document.view.action'), compact('document'), key('action'))
    </x-page-header>

    <div class="flex flex-col gap-6 md:flex-row">
        <div class="md:w-8/12">
            <x-box class="rounded-lg">
                <div class="flex flex-col divide-y">
                    <div class="grid md:grid-cols-12">
                        <div class="md:col-span-5">
                            @livewire(lw('app.document.view.contact'), compact('document'), key('contact'))
                        </div>

                        <div class="md:col-span-7">
                            @livewire(lw('app.document.view.info'), compact('document'), key('info'))
                        </div>
                    </div>

                    @livewire(lw('app.document.view.item'), compact('document'), key('item'))

                    @if ($document->type !== 'delivery-order')
                        @livewire(lw('app.document.view.total'), compact('document'), key('total'))
                    @endif

                    <div class="p-4 grid gap-4">
                        @if ($note = $document->note)
                            <x-form.field label="Note" class="text-sm">
                                {{ $note }}
                            </x-form.field>
                        @endif

                        @if ($footer = $document->footer)
                            <x-form.field label="Footer" class="text-sm">
                                {{ $footer }}
                            </x-form.field>
                        @endif

                        @if (!$note && !$footer)
                            <div class="text-gray-400 text-center">
                                {{ __('No footer') }}
                            </div>
                        @endif
                    </div>
                </div>
            </x-box>
        </div>

        <div class="md:w-4/12">
            <div class="flex flex-col gap-6">
                @livewire(lw('app.document.view.additional-info'), compact('document'), key('info'))

                @if ($document->convertedTo()->count())
                    @livewire(lw('app.document.view.converted'), compact('document'), key('converted'))
                @endif

                @if (
                    $document->type === 'invoice'
                    && ($document->splits()->count() || !in_array($document->status, ['paid', 'partial']))
                )
                    @livewire(lw('app.document.view.split'), compact('document'), key('split'))
                @endif

                @if (in_array($document->type, ['invoice', 'bill']))
                    @livewire(lw('app.document.view.payment'), compact('document'), key('payment'))
                @endif

                @livewire(lw('app.document.view.attachment'), compact('document'), key('attachment'))
            </div>
        </div>
    </div>
    
    <x-shareable :shareable="$document->shareable"/>

    @livewire(lw('app.document.form.email-modal'), compact('document'), key('email-form-modal'))
</div>