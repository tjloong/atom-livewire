<?php

namespace Jiannius\Atom\Traits;

trait BelongsToAccount
{
    public $enabledBelongsToAccountTrait = true;

    /**
     * Boot the trait
     *
     * @return void
     */
    protected static function bootBelongsToAccount()
    {
        static::saving(function ($model) {
            if ($id = optional(auth()->user())->account_id) {
                $model->account_id = $model->account_id ?? $id;
            }
        });
    }

    /**
     * Initialize the trait
     * 
     * @return void
     */
    protected function initializeBelongsToAccount()
    {
        $this->casts['account_id'] = 'integer';
    }

    /**
     * Get account for model
     */
    public function account()
    {
        return $this->belongsTo(get_class(model('account')));
    }

    /**
     * Scope for belongsToAccount
     * 
     * @param Builder $query
     * @param integer $accountId
     * @return Builder
     */
    public function scopeBelongsToAccount($query, $accountId = null)
    {
        $table = $this->getTable();

        if (auth()->user()->isAccountType('root')) {
            if ($accountId) return $query->where($table.'.account_id', $accountId);
            else return $query;
        }
        elseif ($accountId = auth()->user()->account_id) return $query->where($table.'.account_id', $accountId);
        else return $query->whereNull($table.'.id');
    }
}