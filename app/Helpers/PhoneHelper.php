<?php

namespace App\Helpers;

class PhoneHelper
{
    /**
     * Map of dial codes to ISO2 country codes
     * Based on intl-tel-input country data
     * For dial codes shared by multiple countries, the first/main country is used
     */
    private static $dialCodeToCountry = [
        '93' => 'af',   // Afghanistan
        '355' => 'al',  // Albania
        '213' => 'dz',  // Algeria
        '376' => 'ad',  // Andorra
        '244' => 'ao',  // Angola
        '54' => 'ar',   // Argentina
        '374' => 'am',  // Armenia
        '297' => 'aw',  // Aruba
        '247' => 'ac',  // Ascension Island
        '61' => 'au',   // Australia
        '43' => 'at',   // Austria
        '994' => 'az',  // Azerbaijan
        '973' => 'bh',  // Bahrain
        '880' => 'bd',  // Bangladesh
        '375' => 'by',  // Belarus
        '32' => 'be',   // Belgium
        '501' => 'bz',  // Belize
        '229' => 'bj',  // Benin
        '975' => 'bt',  // Bhutan
        '591' => 'bo',  // Bolivia
        '387' => 'ba',  // Bosnia and Herzegovina
        '267' => 'bw',  // Botswana
        '55' => 'br',   // Brazil
        '246' => 'io',  // British Indian Ocean Territory
        '673' => 'bn',  // Brunei
        '359' => 'bg',  // Bulgaria
        '226' => 'bf',  // Burkina Faso
        '257' => 'bi',  // Burundi
        '855' => 'kh',  // Cambodia
        '237' => 'cm',  // Cameroon
        '1' => 'us',    // United States (default for dial code 1)
        '238' => 'cv',  // Cape Verde
        '599' => 'cw',  // Curaçao
        '236' => 'cf',  // Central African Republic
        '235' => 'td',  // Chad
        '56' => 'cl',   // Chile
        '86' => 'cn',   // China
        '57' => 'co',   // Colombia
        '269' => 'km',  // Comoros
        '243' => 'cd',  // Congo (DRC)
        '242' => 'cg',  // Congo (Republic)
        '682' => 'ck',  // Cook Islands
        '506' => 'cr',  // Costa Rica
        '225' => 'ci',  // Côte d'Ivoire
        '385' => 'hr',  // Croatia
        '53' => 'cu',   // Cuba
        '357' => 'cy',  // Cyprus
        '420' => 'cz',  // Czech Republic
        '45' => 'dk',   // Denmark
        '253' => 'dj',  // Djibouti
        '593' => 'ec',  // Ecuador
        '20' => 'eg',   // Egypt
        '503' => 'sv',  // El Salvador
        '240' => 'gq',  // Equatorial Guinea
        '291' => 'er',  // Eritrea
        '372' => 'ee',  // Estonia
        '268' => 'sz',  // Eswatini
        '251' => 'et',  // Ethiopia
        '500' => 'fk',  // Falkland Islands
        '298' => 'fo',  // Faroe Islands
        '679' => 'fj',  // Fiji
        '358' => 'fi',  // Finland
        '33' => 'fr',   // France
        '594' => 'gf',  // French Guiana
        '689' => 'pf',  // French Polynesia
        '241' => 'ga',  // Gabon
        '220' => 'gm',  // Gambia
        '995' => 'ge',  // Georgia
        '49' => 'de',   // Germany
        '233' => 'gh',  // Ghana
        '350' => 'gi',  // Gibraltar
        '30' => 'gr',   // Greece
        '299' => 'gl',  // Greenland
        '590' => 'gp',  // Guadeloupe
        '502' => 'gt',  // Guatemala
        '44' => 'gb',   // United Kingdom
        '224' => 'gn',  // Guinea
        '245' => 'gw',  // Guinea-Bissau
        '592' => 'gy',  // Guyana
        '509' => 'ht',  // Haiti
        '504' => 'hn',  // Honduras
        '852' => 'hk',  // Hong Kong
        '36' => 'hu',   // Hungary
        '354' => 'is',  // Iceland
        '91' => 'in',   // India
        '62' => 'id',   // Indonesia
        '98' => 'ir',   // Iran
        '964' => 'iq',  // Iraq
        '353' => 'ie',  // Ireland
        '972' => 'il',  // Israel
        '39' => 'it',   // Italy
        '81' => 'jp',   // Japan
        '962' => 'jo',  // Jordan
        '7' => 'ru',    // Russia (default for dial code 7)
        '254' => 'ke',  // Kenya
        '686' => 'ki',  // Kiribati
        '383' => 'xk',  // Kosovo
        '965' => 'kw',  // Kuwait
        '996' => 'kg',  // Kyrgyzstan
        '856' => 'la',  // Laos
        '371' => 'lv',  // Latvia
        '961' => 'lb',  // Lebanon
        '266' => 'ls',  // Lesotho
        '231' => 'lr',  // Liberia
        '218' => 'ly',  // Libya
        '423' => 'li',  // Liechtenstein
        '370' => 'lt',  // Lithuania
        '352' => 'lu',  // Luxembourg
        '853' => 'mo',  // Macau
        '261' => 'mg',  // Madagascar
        '265' => 'mw',  // Malawi
        '60' => 'my',   // Malaysia
        '960' => 'mv',  // Maldives
        '223' => 'ml',  // Mali
        '356' => 'mt',  // Malta
        '692' => 'mh',  // Marshall Islands
        '596' => 'mq',  // Martinique
        '222' => 'mr',  // Mauritania
        '230' => 'mu',  // Mauritius
        '262' => 're',  // Réunion
        '52' => 'mx',   // Mexico
        '691' => 'fm',  // Micronesia
        '373' => 'md',  // Moldova
        '377' => 'mc',  // Monaco
        '976' => 'mn',  // Mongolia
        '382' => 'me',  // Montenegro
        '212' => 'ma',  // Morocco
        '258' => 'mz',  // Mozambique
        '95' => 'mm',   // Myanmar
        '264' => 'na',  // Namibia
        '674' => 'nr',  // Nauru
        '977' => 'np',  // Nepal
        '31' => 'nl',   // Netherlands
        '687' => 'nc',  // New Caledonia
        '64' => 'nz',   // New Zealand
        '505' => 'ni',  // Nicaragua
        '227' => 'ne',  // Niger
        '234' => 'ng',  // Nigeria
        '683' => 'nu',  // Niue
        '672' => 'nf',  // Norfolk Island
        '850' => 'kp',  // North Korea
        '389' => 'mk',  // North Macedonia
        '47' => 'no',   // Norway
        '968' => 'om',  // Oman
        '92' => 'pk',   // Pakistan
        '680' => 'pw',  // Palau
        '970' => 'ps',  // Palestine
        '507' => 'pa',  // Panama
        '675' => 'pg',  // Papua New Guinea
        '595' => 'py',  // Paraguay
        '51' => 'pe',   // Peru
        '63' => 'ph',   // Philippines
        '48' => 'pl',   // Poland
        '351' => 'pt',  // Portugal
        '974' => 'qa',  // Qatar
        '40' => 'ro',   // Romania
        '250' => 'rw',  // Rwanda
        '290' => 'sh',  // Saint Helena
        '508' => 'pm',  // Saint Pierre and Miquelon
        '685' => 'ws',  // Samoa
        '378' => 'sm',  // San Marino
        '239' => 'st',  // São Tomé and Príncipe
        '966' => 'sa',  // Saudi Arabia
        '221' => 'sn',  // Senegal
        '381' => 'rs',  // Serbia
        '248' => 'sc',  // Seychelles
        '232' => 'sl',  // Sierra Leone
        '65' => 'sg',   // Singapore
        '421' => 'sk',  // Slovakia
        '386' => 'si',  // Slovenia
        '677' => 'sb',  // Solomon Islands
        '252' => 'so',  // Somalia
        '27' => 'za',   // South Africa
        '82' => 'kr',   // South Korea
        '211' => 'ss',  // South Sudan
        '34' => 'es',   // Spain
        '94' => 'lk',   // Sri Lanka
        '249' => 'sd',  // Sudan
        '597' => 'sr',  // Suriname
        '46' => 'se',   // Sweden
        '41' => 'ch',   // Switzerland
        '963' => 'sy',  // Syria
        '886' => 'tw',  // Taiwan
        '992' => 'tj',  // Tajikistan
        '255' => 'tz',  // Tanzania
        '66' => 'th',   // Thailand
        '670' => 'tl',  // Timor-Leste
        '228' => 'tg',  // Togo
        '690' => 'tk',  // Tokelau
        '676' => 'to',  // Tonga
        '216' => 'tn',  // Tunisia
        '90' => 'tr',   // Turkey
        '993' => 'tm',  // Turkmenistan
        '688' => 'tv',  // Tuvalu
        '256' => 'ug',  // Uganda
        '380' => 'ua',  // Ukraine
        '971' => 'ae',  // United Arab Emirates
        '598' => 'uy',  // Uruguay
        '998' => 'uz',  // Uzbekistan
        '678' => 'vu',  // Vanuatu
        '58' => 've',   // Venezuela
        '84' => 'vn',   // Vietnam
        '681' => 'wf',  // Wallis and Futuna
        '967' => 'ye',  // Yemen
        '260' => 'zm',  // Zambia
        '263' => 'zw',  // Zimbabwe
    ];

    /**
     * Get ISO2 country code from dial code
     *
     * @param string|null $dialCode The dial code (e.g., "966", "+966", or "966")
     * @return string|null The ISO2 country code in lowercase (e.g., "sa") or null if not found
     */
    public static function getCountryCodeFromDialCode(?string $dialCode): ?string
    {
        if (empty($dialCode)) {
            return null;
        }

        // Remove + sign if present and trim
        $dialCode = trim($dialCode, '+');

        // Return the ISO2 code if found, otherwise null
        return self::$dialCodeToCountry[$dialCode] ?? null;
    }
}
