@props([
    'charts' => $attributes->get('charts'), 
    'uid' => $attributes->get('uid', 'dashboard-chart'),
])

@php 
    $charts = collect($charts)
        ->map(fn($chart) => array_merge($chart, [
            'title' => __($chart['title'] ?? null),
            'subtitle' => __($chart['subtitle'] ?? null),
        ]))
        ->toArray()
@endphp

<div
    x-data="{
        charts: @js($charts),
        instance: null,
        selected: 0,
        colors: {
            slate: '#64748b',
            gray: '#9ca3af',
            red: '#ef4444',
            orange: '#f97316',
            yellow: '#eab308',
            green: '#22c55e',
            cyan: '#06b6d4',
            blue: '#3b82f6',
            indigo: '#6366f1',
            purple: '#a855f7',
            black: '#000000',
        },
        get chart () {
            return this.charts[this.selected]
        },
        init () {
            this.draw()
            this.$watch('chart', () => this.draw())
        },
        getColor (dataset) {
            const color = this.colors[dataset.color || 'gray']
            const rgb = hexToRgb(color)

            if (dataset.gradient) {
                const ctx = this.$el.querySelector('#{{ $uid }}').getContext('2d')
                const gradient = ctx.createLinearGradient(0, 0, 0, 450)

                gradient.addColorStop(0, `rgba(${rgb.r}, ${rgb.g}, ${rgb.b}, 0.5)`)
                gradient.addColorStop(0.5, `rgba(${rgb.r}, ${rgb.g}, ${rgb.b}, 0.25)`)
                gradient.addColorStop(1, `rgba(${rgb.r}, ${rgb.g}, ${rgb.b}, 0)`)

                return gradient
            }
            else return color
        },
        destroyIfExists () {
            if (this.instance) this.instance.destroy()
            
            Chart.helpers.each(Chart.instances, (instance) => {
                if (instance.canvas.id === @js($uid)) instance.destroy()
            })
        },
        draw () {
            this.destroyIfExists()
            this.instance = new Chart(this.$el.querySelector('#{{ $uid }}'), {
                type: this.chart.type || 'line',
                options: {
                    maintainAspectRatio: false,
                    elements: {
                        line: {
                            tension: 0.3,
                            borderWidth: 2,
                        },
                        point: { radius: 0 },
                    },
                    plugins: {
                        legend: {
                            align: 'end',
                            labels: {
                                boxWidth: 8,
                                padding: 16,
                                fontSize: 13,
                                fontColor: '#000',
                                usePointStyle: true,
                            },
                        },
                        tooltip: {
                            intersect: false,
                            callbacks: {
                                title: (tooltipItems) => {
                                    const tooltipItem = tooltipItems[0]
                                    return `${tooltipItem.dataset.label} ${tooltipItem.label}`
                                },
                                label: (context) => {
                                    let n = context.raw

                                    if (Number.isFinite(n)) {
                                        if (context.dataset.currency) return currency(n, context.dataset.currency)
                                        else return shortNumber(n)
                                    }
                                    else return n
                                },
                            }
                        },
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false,
                            },
                            ticks: {
                                color: '#888',
                            }
                        },
                        y: {
                            beginAtZero: true,
                            suggestedMax: 100,
                            grid: {
                                display: false,
                            },
                            ticks: {
                                color: '#888',
                                callback: (value, index, values) => {
                                    if (Number.isFinite(value)) return shortNumber(value)
                                    else return value || 0
                                },
                            }
                        },
                    },        
                },
                data: {
                    labels: this.chart.data.labels,
                    datasets: this.chart.data.datasets.map(dataset => ({
                        ...dataset,
                        borderColor: this.getColor(dataset),
                        backgroundColor: this.getColor(dataset),
                        fill: dataset.fill || false,
                    }))
                }
            })
        },
    }"
>
    <x-box class="rounded-xl">
        <x-slot:header class="flex items-center gap-2">
            <div class="grow">
                <div x-text="chart.title" class="font-bold md:text-lg"></div>
                <div x-text="chart.subtitle" class="font-medium text-sm text-gray-500"></div>
            </div>

            @if (count($charts) > 1)
                <div class="shrink-0">
                    <x-dropdown icon="bars">
                        @foreach ($charts as $i => $chart)
                            <x-dropdown.item 
                                :label="data_get($chart, 'title')"
                                x-on:click="selected = {{ $i }}"
                            />
                        @endforeach
                    </x-dropdown>
                </div>
            @endif
        </x-slot:header>

        <div {{ $attributes->class([
            'flex items-center justify-center p-4',
            $attributes->get('class', 'h-80'),
        ]) }}>
            <canvas id="{{ $uid }}" class="w-full h-full"></canvas>
        </div>
    </x-box>
</div>
