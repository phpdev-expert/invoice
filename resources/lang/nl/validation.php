<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'Het :attribute moet worden geaccepteerd.',
    'active_url'           => 'Het :attribute is not a valid URL.',
    'after'                => 'Het :attribute moet later zijn dan :date.',
    'alpha'                => 'Het :attribute mag alleen letters bevatten.',
    'alpha_dash'           => 'Het :attribute mag alleen letters, cijfers en streepjes bevatten.',
    'alpha_num'            => 'Het :attribute mag alleen letters en cijfers bevatten.',
    'array'                => 'Het :attribute moet een array zijn.',
    'before'               => 'Het :attribute moet eerder zijn dan :date.',
    'between'              => [
        'numeric' => 'Het :attribute moet tussen :min en :max zijn.',
        'file'    => 'Het :attribute moet tussen :min en :max kilobytes zijn.',
        'string'  => 'Het :attribute moet tussen :min en :max karakters zijn.',
        'array'   => 'Het :attribute moet tussen :min en :max items zijn.',
    ],
    'boolean'              => 'Het :attribute veld moet True of False zijn.',
    'confirmed'            => 'Het :attribute bevestigingsveld komt niet overeen.',
    'date'                 => 'Het :attribute is geen geldige datum.',
    'date_format'          => 'Het :attribute komt niet overeen met het formaat :format.',
    'different'            => 'Het :attribute en :other moeten verschillen.',
    'digits'               => 'Het :attribute moet :digits tekens zijn.',
    'digits_between'       => 'Het :attribute moet tussen :min en :max tekens zijn.',
    'email'                => 'Het :attribute moet een geldig emailadres zijn.',
    'filled'               => 'Het :attribute veld is verplicht.',
    'exists'               => 'Het geselecteerde :attribute is ongeldig.',
    'image'                => 'Het :attribute moet een afbeelding zijn.',
    'in'                   => 'Het geselecteerde :attribute is ongeldig.',
    'integer'              => 'Het :attribute moet een integer zijn.',
    'ip'                   => 'Het :attribute moet een geldig IP-adres zijn.',
    'max'                  => [
        'numeric' => 'Het :attribute mag niet groter zijn dan :max.',
        'file'    => 'Het :attribute mag niet groter zijn dan :max kilobytes.',
        'string'  => 'Het :attribute mag niet groter zijn dan :max karakters.',
        'array'   => 'Het :attribute mag niet meer bevatten dan :max items.',
    ],
    'mimes'                => 'Het :attribute moet een bestandstype: :values hebben.',
    'min'                  => [
        'numeric' => 'Het :attribute moet minimaal :min zijn.',
        'file'    => 'Het :attribute moet minimaal :min kilobytes zijn.',
        'string'  => 'Het :attribute moet minimaal :min karakters zijn.',
        'array'   => 'Het :attribute moet minimaal :min items hebben.',
    ],
    'not_in'               => 'Het geselecteerde :attribute is ongeldig.',
    'numeric'              => 'Het :attribute moet een cijfer zijn.',
    'regex'                => 'Het :attribute formaat is ongeldig.',
    'required'             => 'Het :attribute veld is vereist.',
    'required_if'          => 'Het :attribute veld is vereist wanneer :other is :value.',
    'required_with'        => 'Het :attribute veld is vereist wanneer :values aanwezig is.',
    'required_with_all'    => 'Het :attribute veld is vereist wanneer :values aanwezig zijn.',
    'required_without'     => 'Het :attribute veld is vereist wanneer :values niet aanwezig is.',
    'required_without_all' => 'Het :attribute veld is vereist wanneer :values niet aanwezig zijn.',
    'same'                 => 'Het :attribute en :other moeten overeen komen.',
    'size'                 => [
        'numeric' => 'Het :attribute moet :size zijn.',
        'file'    => 'Het :attribute moet :size kilobytes zijn.',
        'string'  => 'Het :attribute moet :size karakters zijn.',
        'array'   => 'Het :attribute moet :size items bevatten.',
    ],
    'string'               => 'Het :attribute moet een string zijn.',
    'timezone'             => 'Het :attribute moet een geldige tijdzone zijn.',
    'unique'               => 'Het :attribute is al gekozen.',
    'url'                  => 'Het :attribute URL-formaat is ongeldig.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
