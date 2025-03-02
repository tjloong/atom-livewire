<div class="my-4">
    @if ($paginator->hasPages())
        @php(isset($this->numberOfPaginatorsRendered[$paginator->getPageName()]) ? $this->numberOfPaginatorsRendered[$paginator->getPageName()]++ : $this->numberOfPaginatorsRendered[$paginator->getPageName()] = 1)
        
        <nav role="navigation" aria-label="Pagination Navigation" class="w-full">
            <div class="flex justify-between flex-1 md:hidden">
                <span>
                    @if (!$paginator->onFirstPage())
                        <a
                            wire:click="previousPage('{{ $paginator->getPageName() }}')" 
                            wire:loading.attr="disabled" 
                            dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before"
                            class="flex items-center gap-2 text-zinc-800 py-1 px-3 rounded-md hover:shadow hover:bg-zinc-100"
                        >
                            <x-icon name="left-arrow-alt" size="15px"/> {{ __('Previous') }}
                        </a>
                    @endif
                </span>

                <span>
                    @if ($paginator->hasMorePages())
                        <a
                            wire:click="nextPage('{{ $paginator->getPageName() }}')" 
                            wire:loading.attr="disabled" 
                            dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before"
                            class="flex items-center gap-2 text-zinc-800 py-1 px-3 rounded-md hover:shadow hover:bg-zinc-100"
                        >
                            {{ __('Next') }} <x-icon name="right-arrow-alt" size="15px"/>
                        </a>
                    @endif
                </span>
            </div>

            <div class="flex-wrap items-center justify-between hidden md:flex">
                <div class="flex-shrink-0 text-zinc-700 my-1">
                    <span>{!! __('Showing') !!}</span>
                    <span class="font-medium">{{ $paginator->firstItem() }}</span>
                    <span>{!! __('to') !!}</span>
                    <span class="font-medium">{{ $paginator->lastItem() }}</span>
                    <span>{!! __('of') !!}</span>
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    <span>{!! __('results') !!}</span>
                </div>

                <div class="my-1">
                    <span class="relative z-0 inline-flex rounded-md shadow-sm">
                        <span>
                            {{-- Previous Page Link --}}
                            @if ($paginator->onFirstPage())
                                <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                                    <span class="relative inline-flex items-center py-1.5 px-3 text-sm font-medium text-muted bg-white border border-zinc-300 cursor-default rounded-l-md leading-5" aria-hidden="true">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </span>
                            @else
                                <button wire:click="previousPage('{{ $paginator->getPageName() }}')" dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after" rel="prev" class="relative inline-flex items-center py-1.5 px-3 text-sm font-medium text-muted bg-white border border-zinc-300 rounded-l-md leading-5 hover:text-muted-more focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-zinc-100 active:text-muted transition ease-in-out duration-150" aria-label="{{ __('pagination.previous') }}">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            @endif
                        </span>

                        {{-- Pagination Elements --}}
                        @foreach ($elements as $element)
                            {{-- "Three Dots" Separator --}}
                            @if (is_string($element))
                                <span aria-disabled="true">
                                    <span class="relative inline-flex items-center py-1.5 px-3 -ml-px text-sm font-medium text-zinc-700 bg-white border border-zinc-300 cursor-default leading-5">{{ $element }}</span>
                                </span>
                            @endif

                            {{-- Array Of Links --}}
                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    <span wire:key="paginator-{{ $paginator->getPageName() }}-{{ $this->numberOfPaginatorsRendered[$paginator->getPageName()] }}-page{{ $page }}">
                                        @if ($page == $paginator->currentPage())
                                            <span aria-current="page">
                                                <span class="relative inline-flex items-center py-1.5 px-3 -ml-px text-sm font-medium text-muted bg-zinc-100 border border-zinc-300 cursor-default leading-5">{{ $page }}</span>
                                            </span>
                                        @else
                                            <button wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" class="relative inline-flex items-center py-1.5 px-3 -ml-px text-sm font-medium text-zinc-700 bg-white border border-zinc-300 leading-5 hover:text-muted focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-zinc-100 active:text-zinc-700 transition ease-in-out duration-150" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                                {{ $page }}
                                            </button>
                                        @endif
                                    </span>
                                @endforeach
                            @endif
                        @endforeach

                        <span>
                            {{-- Next Page Link --}}
                            @if ($paginator->hasMorePages())
                                <button wire:click="nextPage('{{ $paginator->getPageName() }}')" dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after" rel="next" class="relative inline-flex items-center py-1.5 px-3 -ml-px text-sm font-medium text-muted bg-white border border-zinc-300 rounded-r-md leading-5 hover:text-muted-more focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-zinc-100 active:text-muted transition ease-in-out duration-150" aria-label="{{ __('pagination.next') }}">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            @else
                                <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                                    <span class="relative inline-flex items-center py-1.5 px-3 -ml-px text-sm font-medium text-muted bg-white border border-zinc-300 cursor-default rounded-r-md leading-5" aria-hidden="true">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </span>
                            @endif
                        </span>
                    </span>
                </div>
            </div>
        </nav>
    @endif
</div>
