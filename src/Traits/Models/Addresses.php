<?php

namespace Jiannius\Atom\Traits\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

trait Addresses
{
    public function addresses() : HasMany
    {
        return $this->hasMany(model('address'));
    }
    
    public function saveAddresses($addresses) : void
    {
        // delete unused addresses
        $this->addresses()
            ->whereNotIn('id', collect($addresses)->pluck('id')->toArray())
            ->delete();

        foreach ($addresses as $data) {
            $address = $this->addresses()->firstOrNew(['id' => get($data, 'id')]);
            $address->fill($data)->save();
        }
    }
}