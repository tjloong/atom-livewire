<?php

namespace Jiannius\Atom\Traits\Models;

use Illuminate\Support\Facades\DB;

trait HasSlug
{
    public $enabledHasSlugTrait = true;

    /**
     * The "booted" method of the model.
     */
    protected static function bootHasSlug()
    {
        // listen to saving event
        static::saving(function ($model) {
            $fields = $model->slugify ?? ['name' => 'slug'];

            foreach ($fields as $from => $to) {
                if ($model->$to && $model->isDirty($to)) $source = data_get($model, $to);
                else if (!$model->$to) $source = data_get($model, $from);

                if (isset($source)) {
                    // non english slug
                    if (strlen($source) != strlen(utf8_decode($source))) {
                        $slug = preg_replace('#(\p{P}|\p{C}|\p{S}|\p{Z})+#u', '-', strtolower($source));
                    }
                    // normal english slug
                    else {
                        $slug = str()->slug($source);
                    }
        
                    // if the generated slug is empty
                    if (!$slug) $slug = strtolower(str()->random(10));
                    // if the generated slug is a number, append something
                    else if (is_numeric($slug)) $slug = $slug . '-' . strtolower(str()->random(5));
                    // remove head and tail dash
                    else $slug = trim($slug, '-');
        
                    // check for uniqueness
                    if (($model->slugMustUnique ?? true)
                        && DB::table($model->getTable())
                            ->where($to, $slug)
                            ->when($model->enabledHasTenantTrait, fn($q) => $q->where('tenant_id', $model->tenant_id))
                            ->count() > 0
                    ) {
                        $slug = $slug.'-'.strtolower(str()->random(5));
                    }
                    
                    $model->$to = $slug;
                }
            }
        });
    }

    /**
     * Scope for find by slug
     */
    public function scopeFindBySlug($query, $slug)
    {
        $table = $this->getTable();

        if (is_numeric($slug)) $query->where($table.'.id', $slug);
        else $query->where($table.'.slug', $slug);

        return $query->first();
    }

    /**
     * Scope for find by slug or failed
     */
    public function scopeFindBySlugOrFail($query, $slug)
    {
        $table = $this->getTable();

        if (is_numeric($slug)) $query->where($table.'.id', $slug);
        else $query->where($table.'.slug', $slug);

        return $query->firstOrFail();
    }
}