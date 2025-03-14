<?php

namespace Jiannius\Atom\Services;

class Select
{
    public $filters;
    public $selected;

    // set static options
    public function static() : array
    {
        return [
            'countries' => $this->countries(),
            'states' => $this->states(),
            'dialCodes' => $this->dialCodes(),
            'currencies' => $this->currencies(),
            'nationalities' => $this->nationalities(),
            'gender' => [
                ['value' => 'm', 'label' => 'Male'],
                ['value' => 'f', 'label' => 'Female'],
            ],
        ];
    }

    // set filters
    public function filters($filters) : mixed
    {
        $this->filters = $filters;

        return $this;
    }

    // set selected
    public function selected($selected) : mixed
    {
        $this->selected = (array) $selected;

        return $this;
    }

    // get options
    public function get($name) : array
    {
        if (str($name)->is('enum.*')) return $this->enum($name);
        elseif (str($name)->is('labels.*')) return $this->labels($name);

        return $this->$name();
    }

    // get static options
    public function getStatic($name) : array
    {
        return get($this->static(), $name, []);
    }

    // enum
    public function enum($name) : array
    {
        $name = (string) str($name)->replace('enum.', '');
        $exclude = get($this->filters, 'exclude');
        $options = enum($name)->all();

        if ($exclude) $options = $options->reject(fn($case) => in_array($case->name, (array) $exclude))->values();

        return $options->map(fn($case) => $case->option())->toArray();
    }

    // labels
    public function labels($name) : array
    {
        $name = (string) str($name)->replace('labels.', '');

        return model('label')->whereIn('id', $this->selected)->union(
            model('label')
            ->whereNotIn('id', $this->selected)
            ->where('type', $name)
            ->when(get($this->filters, 'parent_id'),
                fn($q, $id) => $q->whereIn('parent_id', (array) $id),
                fn($q) => $q->whereNull('parent_id'),
            )
            ->when(get($this->filters, 'search'), fn($q, $search) => $q->search($search))
        )->sequence()->get()->map(fn($label) => [
            'value' => $label->id,
            'label' => $label->name_locale,
            'badge' => [
                'color' => $label->color,
                'label' => $label->name_locale,
            ],
        ])->toArray();
    }

    // countries
    public function countries() : array
    {
        return countries()->map(fn($country) => [
            'value' => get($country, 'code'),
            'label' => get($country, 'name'),
            'flag' => get($country, 'flag'),
        ])->values()->all();
    }

    // states
    public function states() : array
    {
        if ($country = get($this->filters, 'country')) {
            return collect(countries($country.'.states'))
            ->sortBy('name')
            ->map(fn($opt) => [
                'value' => get($opt, 'name'),
                'label' => get($opt, 'name')
            ])
            ->values()
            ->all();
        }

        return [];
    }

    // dial codes
    public function dialCodes() : array
    {
        return countries()->map(fn($val) => [
            'value' => get($val, 'dial_code'),
            'label' => get($val, 'name'),
            'flag' => get($val, 'flag'),
        ])->toArray();
    }

    // currencies
    public function currencies() : array
    {
        $search = (string) str(get($this->filters, 'search'))->upper();

        return currencies()
        ->filter(fn($val) => !empty(get($val, 'code')) && (
            ($this->selected && in_array(get($val, 'code'), $this->selected))
            || get($val, 'code') === $search
            || str(get($val, 'code'))->is([$search, $search.'*', '*'.$search])
        ))
        ->map(fn($currency) => [
            'value' => get($currency, 'code'),
            'label' => collect([
                get($currency, 'code'),
                get($currency, 'symbol'),
            ])->filter()->join(' - '),
        ])
        ->unique('value')
        ->sortBy('label')
        ->values()
        ->all();
    }

    // nationalities
    public function nationalities() : array
    {
        return collect([
            "Afghan", "Albanian", "Algerian", "American", "Andorran", "Angolan", "Antiguans", "Argentinean", "Armenian", "Australian",
            "Austrian", "Azerbaijani", "Bahamian", "Bahraini", "Bangladeshi", "Barbadian", "Barbudans", "Batswana", "Belarusian",
            "Belgian", "Belizean", "Beninese", "Bhutanese", "Bolivian", "Bosnian", "Brazilian", "British", "Bruneian", "Bulgarian",
            "Burkinabe", "Burmese", "Burundian", "Cambodian", "Cameroonian", "Canadian", "Cape Verdean", "Central African", "Chadian",
            "Chilean", "Chinese", "Colombian", "Comoran", "Congolese", "Costa Rican", "Croatian", "Cuban", "Cypriot", "Czech", "Danish",
            "Djibouti", "Dominican", "Dutch", "East Timorese", "Ecuadorean", "Egyptian", "Emirian", "Equatorial Guinean", "Eritrean",
            "Estonian", "Ethiopian", "Fijian", "Filipino", "Finnish", "French", "Gabonese", "Gambian", "Georgian", "German", "Ghanaian",
            "Greek", "Grenadian", "Guatemalan", "Guinea-Bissauan", "Guinean", "Guyanese", "Haitian", "Herzegovinian", "Honduran",
            "Hungarian", "I-Kiribati", "Icelander", "Indian", "Indonesian", "Iranian", "Iraqi", "Irish", "Israeli", "Italian", "Ivorian",
            "Jamaican", "Japanese", "Jordanian", "Kazakhstani", "Kenyan", "Kittian and Nevisian", "Kuwaiti", "Kyrgyz", "Laotian", "Latvian",
            "Lebanese", "Liberian", "Libyan", "Liechtensteiner", "Lithuanian", "Luxembourger", "Macedonian", "Malagasy", "Malawian",
            "Malaysian", "Maldivan", "Malian", "Maltese", "Marshallese", "Mauritanian", "Mauritian", "Mexican", "Micronesian", "Moldovan",
            "Monacan", "Mongolian", "Moroccan", "Mosotho", "Motswana", "Mozambican", "Namibian", "Nauruan", "Nepalese", "New Zealander",
            "Nicaraguan", "Nigerian", "Nigerien", "North Korean", "Northern Irish", "Norwegian", "Omani", "Pakistani", "Palauan", "Panamanian",
            "Papua New Guinean", "Paraguayan", "Peruvian", "Polish", "Portuguese", "Qatari", "Romanian", "Russian", "Rwandan", "Saint Lucian",
            "Salvadoran", "Samoan", "San Marinese", "Sao Tomean", "Saudi", "Scottish", "Senegalese", "Serbian", "Seychellois", "Sierra Leonean",
            "Singaporean", "Slovakian", "Slovenian", "Solomon Islander", "Somali", "South African", "South Korean", "Spanish", "Sri Lankan",
            "Sudanese", "Surinamer", "Swazi", "Swedish", "Swiss", "Syrian", "Taiwanese", "Tajik", "Tanzanian", "Thai", "Togolese", "Tongan",
            "Trinidadian or Tobagonian", "Tunisian", "Turkish", "Tuvaluan", "Ugandan", "Ukrainian", "Uruguayan", "Uzbekistani", "Venezuelan",
            "Vietnamese", "Welsh", "Yemenite", "Zambian", "Zimbabwean",
        ])->map(fn($val) => [
            'value' => $val,
            'label' => $val,
        ])->toArray();
    }
}
