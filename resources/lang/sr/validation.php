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

    'accepted'             => 'The :attribute mora biti prihvaćen.',
    'active_url'           => 'The :attribute nije validan URL.',
    'after'                => 'The :attribute mora biti datum posle :date.',
    'alpha'                => 'The :attribute može samo da sadrži slova.',
    'alpha_dash'           => 'The :attribute može da sadrži samo slova, brojeve i povlake.',
    'alpha_num'            => 'The :attribute može da sadrži samo slova i brojeve.',
    'array'                => 'The :attribute mora biti niz.',
    'before'               => 'The :attribute mora biti datum pre :date.',
    'between'              => [
        'numeric' => 'The :attribute mora biti između :min i :max.',
        'file'    => 'The :attribute mora biti između :min i :max kilobajta.',
        'string'  => 'The :attribute mora biti između :min i :max karaktera.',
        'array'   => 'The :attribute mora imati između :min i :max stavki.',
    ],
    'boolean'              => 'The :attribute polje mora biti tačno ili netačno.',
    'confirmed'            => 'The :attribute potvrda se ne podudara.',
    'date'                 => 'The :attribute nije validan datum.',
    'date_format'          => 'The :attribute ne podudara se format :format.',
    'different'            => 'The :attribute i :other moraju biti različiti.',
    'digits'               => 'The :attribute mora biti :digits brojevi.',
    'digits_between'       => 'The :attribute mora biti između :min i :max brojeva.',
    'email'                => 'The :attribute mora biti validna imejl adresa.',
    'filled'               => 'The :attribute polje je neophodno.',
    'exists'               => 'The izabrani :attribute je validan.',
    'image'                => 'The :attribute mora biti slika.',
    'in'                   => 'The izabrani :attribute nije validan.',
    'integer'              => 'The :attribute mora biti broj.',
    'ip'                   => 'The :attribute mora biti validna IP adresa.',
    'max'                  => [
        'numeric' => 'The :attribute ne može biti veći :max.',
        'file'    => 'The :attribute ne može biti veći :max kilobajta.',
        'string'  => 'The :attribute ne može biti veći :max karaktera.',
        'array'   => 'The :attribute ne može imati više nego :max stavka.',
    ],
    'mimes'                => 'The :attribute mora biti datoteka tipa: :values.',
    'min'                  => [
        'numeric' => 'The :attribute mora biti najmanje :min.',
        'file'    => 'The :attribute mora biti najmanje :min kilobajta.',
        'string'  => 'The :attribute mora biti najmanje :min karaktera.',
        'array'   => 'The :attribute mora imati najmanje :min stavka.',
    ],
    'not_in'               => 'The izabrani :attribute je validan.',
    'numeric'              => 'The :attribute mora biti broj.',
    'regex'                => 'The :attribute format je validan.',
    'required'             => 'The :attribute polje je neophodno.',
    'required_if'          => 'The :attribute polje je neophodno kada :other je :value.',
    'required_with'        => 'The :attribute polje je neophodno kada :values postoji.',
    'required_with_all'    => 'The :attribute polje je neophodno kada :values postoji.',
    'required_without'     => 'The :attribute polje je neophodno kada :values nepostoji.',
    'required_without_all' => 'The :attribute polje je neophodno kada nijedna :values nepostoji.',
    'same'                 => 'The :attribute i :other se moraju podudarati.',
    'size'                 => [
        'numeric' => 'The :attribute mora biti :size.',
        'file'    => 'The :attribute mora biti :size kilobajta.',
        'string'  => 'The :attribute mora biti :size karaktera.',
        'array'   => 'The :attribute mora sadržati :size stavka.',
    ],
    'string'               => 'The :attribute mora biti reč(niz slova).',
    'timezone'             => 'The :attribute mora biti validna vremenska zona.',
    'unique'               => 'The :attribute je već zauzet.',
    'url'                  => 'The :attribute format je validan.',

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
