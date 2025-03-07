import ApexCharts from 'apexcharts'

function generateTrend(el, props) {
    return new ApexCharts(el, {
        chart: {
            type: 'area',
            height: '100%',
            sparkline: { enabled: true },
        },
        series: [{
            data: props.data,
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
        colors: [
            {
                red: '#fda4af',
                green: '#6ee7b7',
                gray: '#d4d4d8',
            }[props.color || 'gray'],
        ],
        ...(props.config || {}),
    })
}

function generateBarChart(el, props) {
    return new ApexCharts(el, {
        chart: {
            type: 'bar',
            height: '100%',
            toolbar: { show: false },
        },
        series: [{
            data: props.data.pluck('value'),
        }],
        plotOptions: {
            bar: {
                columnWidth: '85%',
                borderRadius: 2,
                borderRadiusApplication: 'end',
            },
        },
        dataLabels: {
            enabled: false,
        },
        legend: {
            show: false,
        },
        colors: props.color ? [
            {
                red: '#fda4af',
                green: '#0f766e',
                gray: '#d4d4d8',
                orange: '#ea580c',
            }[props.color] || props.color,
        ] : '#d4d4d8',
        tooltip: {
            custom: ({ series, seriesIndex, dataPointIndex, w }) => {
                let data = props.data[dataPointIndex]
                let tooltip = document.createElement('div')

                tooltip.addClass('bg-black/80 text-sm text-white rounded-md px-3 py-1 shadow-lg')
                tooltip.innerText = data.tooltip
                            
                return tooltip.outerHTML
            },
        },
        grid: {
            borderColor: '#f4f4f5',
        },
        xaxis: {
            axisTicks: { show: false },
            axisBorder: { show: false },
            categories: props.data.pluck('label'),
        },
        yaxis: {
            show: false,
            axisTicks: { show: false },
            axisBorder: { show: false },
        },
        ...(props.max?.value ? {
            annotations: {
                yaxis: [{
                    y: props.max.value,
                    borderColor: 'black',
                    label: {
                        borderColor: 'black',
                        style: {
                            color: 'white',
                            background: 'black',
                            fontSize: '12px',
                        },
                        position: 'center',
                        text: props.max.label,
                    },
                }],
            },
        } : {}),
        ...(props.config || {}),
    })
}

function generateAreaChart(el, props) {
    return new ApexCharts(el, {
        chart: {
            type: 'area',
            height: '100%',
            sparkline: { enabled: true },
        },
        series: [{ 
            data: props.data.map(data => ({
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
        colors: props.color ? [
            {
                red: '#fda4af',
                green: '#0f766e',
                gray: '#d4d4d8',
                orange: '#ea580c',
            }[props.color] || props.color,
        ] : '#d4d4d8',
        tooltip: {
            custom: ({ series, seriesIndex, dataPointIndex, w }) => {
                let data = props.data[dataPointIndex]
                let tooltip = document.createElement('div')

                tooltip.addClass('bg-black/80 text-sm text-white rounded-md px-3 py-1 shadow-lg')
                tooltip.innerText = data.tooltip
                            
                return tooltip.outerHTML
            },
        },
        ...(props.max?.value ? {
            yaxis: {
                min: (props.min?.value || 0) * 1.12,
                max: props.max.value * 1.12,  // add buffer to yaxis to prevent annotation being cut off
            },
            annotations: {
                yaxis: [{
                    y: props.max.value,
                    borderColor: 'black',
                    label: {
                        borderColor: 'black',
                        style: {
                            color: 'white',
                            background: 'black',
                            fontSize: '12px',
                        },
                        position: 'center',
                        text: props.max.label,
                    },
                }],
            },
        } : {}),
        ...(props.config || {}),
    })
}

export default (el, { modifiers, expression }, { evaluateLater, effect, cleanup, nextTick }) => {
    let chart
    let getProps = evaluateLater(expression)

    effect(() => {
        if (chart) return

        getProps(props => {
            let type = props.type || null

            if (type === 'trend') {
                chart = generateTrend(el, props)
            }
            else if (type === 'bar')
                chart = generateBarChart(el, props)
            else {
                chart = generateAreaChart(el, props)
            }

            setTimeout(() => chart.render(), 200)
        })
    })

    cleanup(() => {
        if (!chart) return
        chart.destroy()
        chart = null
    })
}