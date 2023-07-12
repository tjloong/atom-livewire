<?php

namespace Jiannius\Atom\Enums\User;

enum Status: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case BLOCKED = 'blocked';
    case TRASHED = 'trashed';

    public function option()
    {
        return match($this) {
            static::ACTIVE => [
                'value' => $this->value,
                'label' => 'Active',
            ],
            static::INACTIVE => [
                'value' => $this->value,
                'label' => 'Inactive',
            ],
            static::BLOCKED => [
                'value' => $this->value,
                'label' => 'Blocked',
            ],
            static::TRASHED => [
                'value' => $this->value,
                'label' => 'Trashed',
            ],
        };
    }
}