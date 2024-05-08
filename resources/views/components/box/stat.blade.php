@php
$callback = $attributes->get('callback');
@endphp

<div
    wire:ignore
    x-cloak
    x-data="{
        stats: null,
        loading: true,
        callback: @js($callback),

        get trend () {
            if (!this.stats.trend) return

            let colors = {
                green: '#4ade80',
                red: '#f87171',
                gray: '#94a3b8',
            }

            let color = colors.gray
            if (this.stats.trend.increase) color = this.stats.trend.inverted ? colors.red : colors.green
            if (this.stats.trend.decrease) color = this.stats.trend.inverted ? colors.green : colors.red

            let label
            if (this.stats.trend.increase) label = '{{ tr('app.label.increase') }}'
            if (this.stats.trend.decrease) label = '{{ tr('app.label.decrease') }}'

            let empty = this.stats.trend.data.length && !this.stats.trend.data.some(val => (val > 0 || val < 0))

            return { ...this.stats.trend, color, label, empty }
        },

        get chart () {
            if (!this.stats.chart) return

            let max = this.stats.chart.data.reduce((prev, current) => (
                prev.value > current.value ? prev : current
            ))

            let min = this.stats.chart.data.reduce((prev, current) => (
                prev.value < current.value ? prev : current
            ))

            let data = this.stats.chart.data
            let color = this.stats.chart.color
            let empty = this.stats.chart.data.length && !this.stats.chart.data.some(val => (val.value > 0 || val.value < 0))

            return { data, min, max, color, empty }
        },

        init () {
            this.fetch()
        },

        fetch () {
            this.loading = true

            this.$wire.call(this.callback)
            .then(res => this.stats = res)
            .then(() => this.loading = false)
        },
    }"
    x-on:refresh-stats.window="fetch()"
    class="relative bg-white border rounded-xl overflow-hidden py-5 px-6 hover:ring-1 hover:ring-gray-200 hover:ring-offset-2">
    <template x-if="loading || !stats">
        <div class="flex flex-col gap-4 animate-pulse">
            <div class="h-4 w-1/2 bg-gray-200 rounded-md"></div>
            <div class="h-10 w-full bg-gray-200 rounded-md"></div>
            <div class="h-4 w-1/2 bg-gray-200 rounded-md"></div>
        </div>
    </template>

    <template x-if="!loading">
        <div class="flex flex-col gap-1 w-full h-full">
            <div>
                <div x-show="stats.label" x-text="stats.label" class="font-medium text-gray-500"></div>
                <div x-show="stats.value" x-text="stats.value" class="text-3xl font-bold"></div>
            </div>

            <template x-if="trend">
                <div class="grow flex flex-col gap-2">
                    <div class="shrink-0" x-show="trend.decrease || trend.increase">
                        <div
                            x-bind:class="{
                                'bg-red-100 text-red-500 border border-red-200': trend.decrease || (trend.increase && trend.inverted),
                                'bg-green-100 text-green-500 border border-green-200': trend.increase || (trend.decrease && trend.inverted),
                            }"
                            class="inline-flex items-center gap-2 font-medium text-sm px-2 rounded-md">
                            <div x-text="trend.increase || trend.decrease"></div>
                            <div x-text="trend.label" class="lowercase"></div>
                            <x-icon x-show="trend.increase" name="arrow-trend-up"/>
                            <x-icon x-show="trend.decrease" name="arrow-trend-down"/>
                        </div>
                    </div>

                    <div 
                        x-data="{
                            apexchart: null,

                            init () {
                                if (trend.empty) return
                                
                                this.apexchart = new ApexCharts(this.$refs.chart, {
                                    chart: {
                                        type: 'area',
                                        height: '100%',
                                        sparkline: { enabled: true },
                                    },
                                    series: [{
                                        data: trend.data,
                                    }],
                                    fill: {
                                        type: 'solid',
                                        opacity: 0.2,
                                    },
                                    stroke: {
                                        width: 1,
                                        curve: 'smooth',
                                    },
                                    tooltip: {
                                        enabled: false,
                                    },
                                    colors: [trend.color].filter(Boolean),
                                })
            
                                this.$nextTick(() => this.apexchart.render())
                                this.$watch('loading', loading => loading && this.apexchart?.destroy())
                            },
                        }"
                        class="relative grow h-6 -mx-6 -mb-5">
                        <div x-ref="chart" class="absolute inset-0"></div>
                    </div>
                </div>
            </template>

            <template x-if="chart">
                <div
                    x-data="{
                        apexchart: null,

                        init () {
                            if (chart.empty) return

                            this.apexchart = new ApexCharts(this.$refs.chart, {
                                chart: {
                                    type: 'area',
                                    height: '100%',
                                    sparkline: { enabled: true },
                                },
                                series: [{ 
                                    data: chart.data.map(data => ({
                                        x: data.label,
                                        y: data.value,
                                    })),
                                }],
                                xaxis: {
                                    type: 'category',
                                },
                                stroke: {
                                    width: 1,
                                    curve: 'smooth',
                                },
                                colors: [chart.color].filter(Boolean),
                                tooltip: {
                                    custom: ({ series, seriesIndex, dataPointIndex, w }) => {
                                        let data = chart.data[dataPointIndex]
                                        let body = `${data.label} - ${data.format}`
    
                                        let tooltip = document.createElement('div')
                                        tooltip.addClass('bg-black/80 text-sm text-white rounded-md px-3 py-1 shadow-lg')
                                        tooltip.innerText = body
                                                    
                                        return tooltip.outerHTML
                                    },
                                },
                                ...(chart.max.value ? {
                                    yaxis: {
                                        min: chart.min?.value || 0,
                                        max: chart.max.value * 1.08,  // add buffer to yaxis to prevent annotation being cut off
                                    },
                                    annotations: {
                                        yaxis: [{
                                            y: chart.max.value,
                                            borderColor: 'black',
                                            label: {
                                                borderColor: 'black',
                                                style: {
                                                    color: 'white',
                                                    background: 'black',
                                                    fontSize: '12px',
                                                },
                                                position: 'center',
                                                text: chart.max.format,
                                            },
                                        }],
                                    },
                                } : {})
                            })
        
                            this.$nextTick(() => this.apexchart.render())
                            this.$watch('loading', loading => loading && this.apexchart?.destroy())
                        },
                    }"
                    class="relative h-72 -mx-5 -mb-5">
                    <div x-show="chart.empty" class="w-full h-full flex items-center justify-center">
                        <x-inline label="app.label.no-data" icon="chart-line" class="font-medium text-gray-400"/>
                    </div>
                    <div x-ref="chart" class="absolute inset-0"></div>
                </div>
            </template>
        </div>
    </template>
</div>
