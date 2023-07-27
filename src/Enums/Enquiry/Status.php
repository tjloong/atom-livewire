<?php

namespace Jiannius\Atom\Enums\Enquiry;

enum Status: string
{
    case PENDING = 'pending';
    case CLOSED = 'closed';

    public function color()
    {
        return match($this) {
            static::PENDING => 'blue',
            static::CLOSED => 'gray',
        };
    }

    public function option()
    {
        return match($this) {
            static::PENDING => ['value' => $this->value, 'label' => str()->headline($this->value)],
            static::CLOSED => ['value' => $this->value, 'label' => str()->headline($this->value)],
        };
    }
}