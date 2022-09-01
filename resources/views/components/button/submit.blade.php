<x-button 
    :icon="$attributes->get('icon', 'check')"
    :color="$attributes->get('color', 'green')"
    {{ 
        $attributes->merge([
            'type' => 'submit',
            'label' => $attributes->get('label', 'Save')
        ])->except(['icon', 'color'])
    }}
/>
