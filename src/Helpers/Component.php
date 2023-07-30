<?php

/**
 * Component ID
 */
function component_id($attributes, $default = null)
{
    if ($attributes->get('uuid') === true) return str()->uuid();
    if ($attributes->get('ulid') === true) return str()->ulid();

    if ($id = $attributes->get('id') ?? $attributes->get('uid') ?? $attributes->get('name') ?? null) {
        return $id;
    }

    if ($name = $attributes->wire('model')->value() ?? $attributes->get('name') ?? component_label($attributes) ?? null) {
        return str($name)->replace('.', '-')->slug()->toString();
    }

    return $default;
}

/**
 * Component label
 */
function component_label($attributes, $default = null, $trans = true)
{
    if ($attributes->get('label') === false) return null;
    
    $label = $attributes->get('label');

    if (!$label) {
        if ($name = $attributes->get('name') ?? $attributes->wire('model')->value() ?? null) {
            $last = last(explode('.', $name));
            $last = str($last)->is('*_id') ? str($last)->replaceLast('_id', '')->toString() : $last;
            $label = str($last)->headline()->toString();
        }
        else $label = $default;
    }

    return $trans ? __($label) : $label;
}

/**
 * Component error
 */
function component_error($errors, $attributes)
{
    if (!$errors) return false;

    $name = $attributes->get('name');
    $model = $attributes->wire('model')->value();

    return $errors->first($name ?? $model);
}
