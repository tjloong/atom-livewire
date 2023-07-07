<x-form header="Comments">
    <div class="flex flex-col divide-y">
        @if ($this->comments->total())
            @foreach ($this->comments as $comment)
                <div class="p-4 grid gap-2">
                    <div class="flex items-center justify-between gap-4">
                        <div class="text-sm font-medium">
                            {{ $comment->createdBy->name }} - <span class="text-gray-400">{{ format_date($comment->created_at, 'datetime') }}</span>
                        </div>

                        @if ($comment->created_by === user()->id)
                            <x-close.delete
                                title="Delete Comment"
                                message="Are you sure to delete this comment?"
                                :params="$comment->id"
                            />
                        @endif
                    </div>

                    <div class="text-gray-600">{!! nl2br($comment->body) !!}</div>
                </div>
            @endforeach

            @if ($this->comments->hasPages())
                <div class="p-4">
                    {!! $this->comments->links() !!}
                </div>
            @endif
        @endif

        <div class="p-4">
            <x-form.textarea wire:model.defer="content" placeholder="Comments" :label="false"/>
        </div>
    </div>

    <x-slot:foot>
        <x-button.submit icon="send" label="Post Comment"/>
    </x-slot:foot>
</x-form>