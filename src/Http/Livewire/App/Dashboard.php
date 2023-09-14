<?php

namespace Jiannius\Atom\Http\Livewire\App;

use Illuminate\Support\Facades\DB;
use Jiannius\Atom\Component;

class Dashboard extends Component
{
    public $filters = [
        'date_range' => [],
    ];

    // mount
    public function mount()
    {
        $this->filters = [
            'date_range' => collect([
                format_date(today()->startOfDay()->subDays(30), 'carbon')->toDateString(),
                format_date(now(), 'carbon')->toDateString(),
            ])->join(' to '),
        ];
    }

    // get date range property
    public function getDateRangeProperty()
    {
        $daterange = data_get($this->filters, 'date_range');
        $split = explode(' to ', $daterange);

        // range in utc
        $from = format_date($split[0], 'carbon')->startOfDay()->setTimezone('utc');
        $to = format_date($split[1], 'carbon')->endOfDay()->setTimezone('utc');

        return [
            'from' => $from,
            'to' => $to,
            'range' => [$from->toDateString(), $to->toDateString()],
            'diff' => [
                'days' => $from->copy()->diffInDays($to) + 1,
                'months' => $from->copy()->diffInMonths($to->copy()->endOfMonth()),
                'years' => $from->copy()->diffInYears($to->copy()->endOfYear()),
            ],
        ];
    }

    // get sections property
    public function getSectionsProperty()
    {
        return [
            collect([
                has_table('blogs') ? [
                    'title' => 'Total Articles',
                    'type' => 'statbox',
                    'count' => model('blog')->whereBetween('created_at', data_get($this->filters, 'date'))->count(),
                ] : null,

                has_table('blogs') ? [
                    'title' => 'Total Published',
                    'type' => 'statbox',
                    'count' => model('blog')->whereBetween('published_at', data_get($this->filters, 'date'))->count(),
                ] : null,

                has_table('enquiries') ? [
                    'title' => 'Total Enquiries',
                    'type' => 'statbox',
                    'count' => model('enquiry')->whereBetween('created_at', data_get($this->filters, 'date'))->count(),
                ] : null,

                has_table('enquiries') ? [
                    'title' => 'Total Pending Enquiries',
                    'type' => 'statbox',
                    'count' => model('enquiry')->whereBetween('created_at', data_get($this->filters, 'date'))->where('status', 'pending')->count(),
                ] : null,
            ])->filter()->values()->all(),
        ];
    }

    // get chart date range breakdown
    public function getChartDateRangeBreakdown($eloq = null)
    {
        $from = data_get($this->dateRange, 'from')->setTimezone(user('pref.timezone'));
        $breakdown = [];

        $interval = 'daily';
        if (data_get($this->dateRange, 'diff.months') > 12) $interval = 'yearly';
        elseif (data_get($this->dateRange, 'diff.months') > 1) $interval = 'monthly';

        $n = [
            'daily' => data_get($this->dateRange, 'diff.days'),
            'monthly' => data_get($this->dateRange, 'diff.months'),
            'yearly' => data_get($this->dateRange, 'diff.years'),
        ][$interval];

        for ($i = 0; $i <= $n; $i++) {
            $carbon = [
                'daily' => $from->copy()->addDays($i),
                'monthly' => $from->copy()->addMonthsNoOverflow($i),
                'yearly' => $from->copy()->addYears($i),
            ][$interval];

            $label = [
                'daily' => $carbon->day.' '.$carbon->shortEnglishMonth,
                'monthly' => $carbon->shortEnglishMonth.'\''.$carbon->format('y'),
                'yearly' => $carbon->year,
            ][$interval];

            if ($eloq) {
                $query = DB::query()->fromSub($eloq, 'data')
                    ->when($interval === 'daily', fn($q) => $q
                        ->selectRaw('DAY(date) AS day, DATE_FORMAT(date, "%c") AS month, SUM(total) AS total')
                        ->groupBy(['day', 'month'])
                        ->orderBy('day')
                        ->orderBy('month')
                    )
                    ->when($interval === 'monthly', fn($q) => $q
                        ->selectRaw('DATE_FORMAT(date, "%c") AS month, YEAR(date) AS year, SUM(total) AS total')
                        ->groupBy(['month', 'year'])
                        ->orderBy('month')
                        ->orderBy('year')
                    )
                    ->when($interval === 'yearly', fn($q) => $q
                        ->selectRaw('YEAR(date) AS year, SUM(total) AS total')
                        ->groupBy('year')
                        ->orderBy('year')
                    )
                    ->get();

                $result = $query
                    ->when($interval === 'daily', fn($col) => $col
                        ->where('day', $carbon->day)
                        ->where('month', $carbon->month)
                    )
                    ->when($interval === 'monthly', fn($col) => $col
                        ->where('month', $carbon->month)
                        ->where('year', $carbon->year)
                    )
                    ->when($interval === 'yearly', fn($col) => $col
                        ->where('year', $carbon->year)
                    )
                    ->first();

                $breakdown[$label] = $result ? (float)$result->total : 0;
            }
            else array_push($breakdown, $label);
        }

        return $breakdown;
    }

    // render
    public function render()
    {
        return atom_view('app.dashboard');
    }
}