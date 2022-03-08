<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Sign-Ups"/>

    <x-table :total="$users->total()" :links="$users->links()" export>
        <x-slot name="head">
            <x-table head sort="created_at">Date</x-table>
            <x-table head sort="name">Sign-Up</x-table>
            <x-table head>Contact</x-table>
            
            @module('plans')
                <x-table head align="right">Plans</x-table>
            @endmodule

            <x-table head/>
        </x-slot>

        <x-slot name="body">
            @foreach ($users as $user)
                <x-table row>
                    <x-table cell>
                        {{ format_date($user->created_at) }}
                        <div class="text-xs font-medium text-gray-500">{{ format_date($user->created_at, 'time') }}</div>
                    </x-table>

                    <x-table cell>
                        <a href="{{ route('signup.update', [$user->id]) }}">
                            {{ $user->name }}
                        </a>
                    </x-table>

                    <x-table cell>
                        @foreach (array_filter([$user->email ?? $user->signup->email, $user->signup->phone]) as $contact)
                            <div>{{ $contact }}</div>
                        @endforeach
                    </x-table>

                    @module('plans')
                        <x-table cell>
                            <div class="flex flex-wrap items-center gap-1">
                                @foreach ($user->signup->subscriptions as $subscription)
                                    <span class="py-0.5 px-2 bg-gray-100 rounded text-xs uppercase">
                                        {{ str($subscription->plan->name)->limit(15) }}
                                    </span>
                                @endforeach
                            </div>
                        </x-table>
                    @endmodule

                    <x-table cell class="text-right">
                        <x-badge>{{ $user->signup->status }}</x-badge>
                    </x-table>
                </x-table>
            @endforeach
        </x-slot>
    </x-table>
</div>