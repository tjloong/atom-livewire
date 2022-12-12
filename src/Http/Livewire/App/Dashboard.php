<?php

namespace Jiannius\Atom\Http\Livewire\App;

use Livewire\Component;

class Dashboard extends Component
{
    public $filters = [
        'date' => [],
        'team' => null,
    ];

    /**
     * Mount
     */
    public function mount()
    {
        breadcrumbs()->home('Dashboard');

        $this->fill([
            'filters.date' => [
                format_date(today()->startOfDay()->subDays(30), 'carbon')->toDateString(),
                format_date(now(), 'carbon')->toDateString(),
            ],
        ]);
    }

    /**
     * Get date range property
     */
    public function getDateRangeProperty()
    {
        $date = data_get($this->filters, 'date');

        // range in utc
        $from = format_date($date[0], 'carbon')->startOfDay()->setTimezone('utc');
        $to = format_date($date[1], 'carbon')->endOfDay()->setTimezone('utc');

        return [
            'from' => $from,
            'to' => $to,
            'diff' => [
                'days' => $from->copy()->diffInDays($to),
                'months' => $from->copy()->diffInMonths($to->copy()->endOfMonth()),
                'years' => $from->copy()->diffInYears($to->copy()->endOfYear()),
            ],
        ];
    }

    /**
     * Get teams property
     */
    public function getTeamsProperty()
    {
        return model('team')
            ->when(
                model('team')->enabledBelongsToAccountTrait,
                fn($q) => $q->belongsToAccount(),
            )
            ->orderBy('name')
            ->get();
    }

    /**
     * Get sections property
     */
    public function getSectionsProperty()
    {
        return [
            [
                enabled_module('blogs') ? [
                    'title' => 'Total Articles',
                    'type' => 'statbox',
                    'count' => model('blog')->whereBetween('created_at', data_get($this->filters, 'date'))->count(),
                ] : null,

                enabled_module('blogs') ? [
                    'title' => 'Total Published',
                    'type' => 'statbox',
                    'count' => model('blog')->whereBetween('published_at', data_get($this->filters, 'date'))->count(),
                ] : null,

                enabled_module('enquiries') ? [
                    'title' => 'Total Enquiries',
                    'type' => 'statbox',
                    'count' => model('enquiry')->whereBetween('created_at', data_get($this->filters, 'date'))->count(),
                ] : null,

                enabled_module('enquiries') ? [
                    'title' => 'Total Pending Enquiries',
                    'type' => 'statbox',
                    'count' => model('enquiry')->whereBetween('created_at', data_get($this->filters, 'date'))->where('status', 'pending')->count(),
                ] : null,
            ],
        ];
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.dashboard');
    }
}