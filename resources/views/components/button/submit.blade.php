<x-button type="submit" c="green"
    :icon="$attributes->get('icon', 'check')"
    :label="$attributes->get('label', 'common.label.save')"
    {{ $attributes->except('c', 'icon', 'label') }}/>
