<?php

namespace Jiannius\Atom\Models;

use App\Models\User;
use Jiannius\Atom\Traits\HasOwner;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasOwner;
    
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get users for team
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'teams_users');
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
        return $query->where(
            fn($q) => $q
                ->where('name', 'like', "%$search%")
                ->orWhereHas('users', fn($q) => $q->search($search))
        );
    }

    /**
     * Scope for user id
     * 
     * @param Builder $query
     * @param integer $id
     * @return Builder
     */
    public function scopeUserId($query, $id)
    {
        return $query->whereHas('users', fn($q) => $q->whereIn('users.id', (array)$id));
    }
}
