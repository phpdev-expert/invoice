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

    'accepted'             => ':attribute muss akzeptiert werden.',
	"active_url"           => ':attribute ist keine valide URL.',
	"after"                => ':attribute muss nach :date sein.',
	"alpha"                => ':attribute darf nur Buchstaben enthalten.',
	"alpha_dash"           => ':attribute darf nur Buchstaben, Zahlen und Gedankenstriche enthalten.',
	"alpha_num"            => ':attribute darf nur Buchstaben und Zahlen enthalten.',
	"array"                => ':attribute muss ein Array sein.',
	"before"               => ':attribute muss vor folgendem Datum liegen :date.',
	"between"              => [
		"numeric" => ':attribute muss zwischen :min und :max liegen.',
		"file"    => ':attribute muss zwischen :min und :max kilobytes liegen.',
		"string"  => ':attribute muss zwischen :min und :max Zeichen lang sein.',
		"array"   => ':attribute muss zwischen :min und :max Einträgen haben.',
	]
    'boolean'              => ':attribute Feld muss wahr oder falsch sein.',
	"confirmed"            => ':attribute wurde nicht bestätigt.',
	"date"                 => ':attribute ist kein korrektes Datum.',
	"date_format"          => ':attribute passt nicht zu folgendem Format :format.',
	"different"            => ':attribute und :other müssen verschieden sein.',
	"digits"               => ':attribute muss :digits Zeichen haben.',
	"digits_between"       => ':attribute muss zwischen :min und :max Zeichen haben.',
	"email"                => ':attribute ist keine E-Mail Adresse.',
    'filled'               => ':attribute wird benötigt.',
	"exists"               => ':attribute existiert schon.',
	"image"                => ':attribute muss ein Bild sein.',
	"in"                   => ' Das Format von :attribute ist falsch.',
	"integer"              => ':attribute muss eine Zahl sein.',
	"ip"                   => ':attribute muss eine IP-Adresse sein.',
    'max'                  => [
		"numeric" => ':attribute muss größer als :max sein.',
		"file"    => ':attribute darf nicht größer als :max kilobytes sein.',
		"string"  => ':attribute darf nicht länger als :max Zeichen lang sein.',
		"array"   => ':attribute darf nicht mehr als :max Einträge haben.',
    ],
	"mimes"                => ':attribute muss eines der folgenden Formate haben: :values.',
    'min'                  => [
		"numeric" => ':attribute muss größer als :min.',
		"file"    => ':attribute muss mindestens :min kilobytes groß sein.',
		"string"  => ':attribute muss mindestens :min Zeichen lang sein.',
		"array"   => ':attribute muss mindestens :min Einträge haben.',
    ],
	"not_in"               => ':attribute ist falsch.',
	"numeric"              => ':attribute muss ein Zahlenwert sein.',
	"regex"                => ':attribute hate ein falsches Format.',
	"required"             => 'Das Feld :attribute ist verpflichtend.',
	"required_if"          => 'Das Feld :attribute ist verpflichtend, wenn :other den Wert :value hat.',
	"required_with"        => 'Das Feld :attribute ist verpflichtend, wenn :values angegeben ist.',
    'required_with_all'    => 'Das Feld :attribute ist verpflichtend, wenn :values angegeben ist.',
	"required_without"     => 'Das Feld :attribute ist verpflichtend, wenn :values nicht angegeben ist.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
	"same"                 => ':attribute und :other müssen gleich sein.',
    'size'                 => [
		"numeric" => ':attribute muss die Länge :size haben.',
		"file"    => ':attribute muss :size kilobytes groß sein.',
		"string"  => ':attribute muss :size Zeichen haben.',
		"array"   => ':attribute muss :size Einträge haben.',
    ],
    'string'               => ':attribute muss eine Zeichenkette sein.',
    'timezone'             => ':attribute muss eine gültige Zone sein.',
	"unique"               => ':attribute ist schon vergeben.',
	"url"                  => 'Das Format :attribute ist ungültig.',

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
