<?php

namespace Jiannius\Atom\Traits\Models;

trait HasPlan
{
    /**
     * Check user is subscribed to plan
     */
    public function isSubscribedToPlan($arg): bool
    {
        $query = $this->subscriptions()->status('active');

        if (is_string($arg)) {
            return $query->whereHas('price', fn($q) => $q
                ->whereHas('plan', fn($q) => $q->where('slug', $arg))
            )->count() > 0;
        }
        else {
            return $query
                ->when(
                    str(get_class($arg))->is('*\PlanPrice'),
                    fn($q) => $q->where('plan_price_id', $arg->id),
                )
                ->when(
                    str(get_class($arg))->is('*\Plan'),
                    fn($q) => $q->whereHas('price', fn($q) => $q->where('plan_id', $arg->id))
                )
                ->count() > 0;
        }
    }

    /**
     * Check user has tried plan
     */
    public function hasTriedPlan($plan): bool
    {
        return $this->subscriptions()
            ->whereHas('price', fn($q) => $q->where('plan_id', $plan->id))
            ->count() > 0;
    }
}