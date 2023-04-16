<div class="max-w-screen-md mx-auto">
    <x-page-header :title="$document->type === 'bill' ? 'Issue Payment' : 'Receive Payment'" back/>
    @livewire('app.document.payment.form', compact('payment'))
</div>