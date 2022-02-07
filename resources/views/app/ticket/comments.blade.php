<x-box>
    <x-slot name="header">Comments</x-slot>

    <div class="grid divide-y">
        @if ($comments->total())
            @foreach ($comments as $comment)
                <div class="p-4 grid gap-2">
                    <div class="flex items-center justify-between gap-4">
                        <div class="text-xs font-medium">
                            {{ $comment->creator->name }} - <span class="text-gray-400">{{ format_date($comment->created_at, 'datetime') }}</span>
                        </div>

                        @if ($comment->created_by === auth()->id())
                            <a class="text-red-500" x-tooltip="Delete" x-on:click="$dispatch('confirm', {
                                title: 'Delete Comment',
                                message: 'Are you sure to delete this comment?',
                                type: 'error',
                                onConfirmed: () => $wire.delete({{ $comment->id }}),
                            })">
                                <x-icon name="trash" size="14px"/>
                            </a>
                        @endif
                    </div>

                    <div class="text-gray-600">{!! nl2br($comment->body) !!}</div>
                </div>
            @endforeach

            @if ($comments->hasPages())
                <div class="p-4">
                    {{ $comments->links() }}
                </div>
            @endif
        @endif

        <div class="p-5">
            <x-input.textarea wire:model.defer="content" placeholder="Comments" :error="$errors->first('content')"/>
        </div>
    </div>

    <x-slot name="buttons">
        <x-button icon="check" color="green" wire:click="submit">
            Post Comment
        </x-button>
    </x-slot>
</x-box>