<?php

namespace Jiannius\Atom\Models;

use App\Models\User;
use Jiannius\Atom\Models\Ability;
use Jiannius\Atom\Traits\HasSlug;
use Jiannius\Atom\Traits\HasOwner;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasSlug;
    use HasOwner;
    
    protected $guarded = [];

    /**
     * Get abilities for role
     */
    public function abilities()
    {
        return $this->belongsToMany(Ability::class, 'abilities_roles');
    }

    /**
     * Get users for role
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Scope for fussy search
     * 
     * @param Builder $query
     * @param string $search
     * @return Builder
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%$search%");
    }

    /**
     * Scope for assignable role
     * 
     * @param Builder $query
     * @return Builder
     */
    public function scopeAssignables($query)
    {
        if (request()->user()->isRole('root')) {
            return $query->where('is_system', true)->orWhereNull('created_by')->orWhereHas('creator', 
                fn($q) => $q->whereHas('role', fn($q) => $q->where('scope', 'root'))
            );
        }
        else {
            return $query->where('scope', '<>', 'root')
                ->when($this->isMultiTenant, fn($q) => $q->where('tenant_id', request()->user()->tenant_id));
        }
    }

    /**
     * Get is_root attribute
     * 
     * @return boolean
     */
    public function getIsRootAttribute()
    {
        return $this->scope === 'root';
    }

    /**
     * Get access_description attribute
     * 
     * @return object
     */
    public function getScopeDescriptionAttribute()
    {
        return self::getScopeDescription($this->scope);
    }

    /**
     * Get scope description
     * 
     * @return object
     */
    public static function getScopeDescription($scope)
    {
        $descriptions = [
            'root' => [
                'name' => 'root',
                'label' => 'Root',
                'caption' => 'Can manage everthing as root',
            ],
            'global' => [
                'name' => 'global',
                'label' => 'Global',
                'caption' => 'Can manage all records',
            ],
            'restrict' => [
                'name' => 'restrict',
                'label' => 'Restricted',
                'caption' => 'Can only manage own records',
            ],
        ];

        return $descriptions[$scope];
    }
}
