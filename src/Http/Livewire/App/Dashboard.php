<?php

namespace Jiannius\Atom\Http\Livewire\App;

use Livewire\Component;
use Carbon\Carbon;

class Dashboard extends Component
{
    public $date;

    /**
     * Mount
     */
    public function mount()
    {
        breadcrumbs()->home('Dashboard');

        $this->date = [
            format_date(today()->startOfDay()->subDays(30), 'carbon')->toDateString(),
            format_date(now(), 'carbon')->toDateString(),
        ];
    }

    /**
     * Get date range property
     */
    public function getDateRangeProperty()
    {
        // range in utc
        $from = format_date($this->date[0], 'carbon')->startOfDay()->setTimezone('utc');
        $to = format_date($this->date[1], 'carbon')->endOfDay()->setTimezone('utc');

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
     * Get blogs property
     */
    public function getBlogsProperty()
    {
        if (!enabled_module('blogs')) return;

        return [
            'count' => model('blog')->whereBetween('created_at', $this->date)->count(),
            'published' => model('blog')->whereBetween('published_at', $this->date)->count(),
        ];
    }

    /**
     * Get enquiries property
     */
    public function getEnquiriesProperty()
    {
        if (!enabled_module('enquiries')) return;

        return [
            'count' => model('enquiry')->whereBetween('created_at', $this->date)->count(),
            'pending' => model('enquiry')->whereBetween('created_at', $this->date)->where('status', 'pending')->count(),
        ];
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.dashboard');
    }
}