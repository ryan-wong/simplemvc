<?php

/**
 * Reason I put all view helper in one class is because it makes finding view helpers easier with an IDE.
 * @author Ryan Wong
 * @version 1.0
 * @category Optional
 * @package Gear
 * @copyright (c) 2013, Ryan Wong
 */
class Gear_ViewHelper {

    public function __construct() {
        
    }

    /**
     * translate message to language specified by browser by 
     * $_SERVER['HTTP_ACCEPT_LANGUAGE'] field
     * @param string $message
     * @return string
     */
    public static function __($message) {
        $lang = getLanguage();
        $fileName = ROOT . DS . "public" . DS . "language" . DS . "{$lang}.php";
        if (file_exists($fileName)) {
            include $fileName;
            if (isset($lang) && !empty($lang[$message])) {
                return $lang[$message];
            }
        }
        return $message;
    }

    /**
     * 
     * @param array $csvArray
     * @param string $fileName
     */
    public function csv(array $csvArray, $fileName = 'test.csv') {
        $csvStr = '';
        foreach ($csvArray as $fields) {
            $count = count($fields);
            $i = 0;
            foreach ($fields as $field) {
                $csvStr .= $field;
                if ($i < $count - 1) {
                    $csvStr.= ',';
                } else {
                    $csvStr .= "\n";
                }
                $i++;
            }
        }
        header("Content-type: application/force-download");
        header("Content-Disposition: attachment; filename=$fileName.csv;size=" . strlen($csvStr));
        echo $csvStr;
        exit;
    }

    /**
     * get country by country code
     * @param string $countryCode
     * @return string
     */
    public function country($countryCode) {
        if (isset(self::$countryList[$countryCode])) {
            return self::$countryList[$countryCode];
        }
        return 'No Country';
    }

    /**
     * get country code from country
     * @param string $country
     * @return string
     */
    public function countryCode($country) {
        foreach (self::$countryList as $k => $v) {
            if ($country == strtolower($v)) {
                return $k;
            }
        }
        return 'No Country Code';
    }

    /**
     * 
     * @param array $data
     * @param string $callback
     * @return string
     */
    public function jsonp($data, $callback = "callback") {
        $json = json_encode($data);
        return "$callback($json)";
    }

    /**
     * 
     * @param string $label
     * @param string $value
     * @param array $options {container => p, minWidth=>180px, padding => 4px, escape => false, helper=>no helper selected, extra=> {url,target=>not blank, text=> displaytext}}
     * @return string
     */
    public function labelValue($label, $value, array $options = array()) {
        $container = (isset($options['container'])) ? $options['container'] : "p";
        $minWidth = (isset($options['minWidth'])) ? $options['minWidth'] : "180px";
        $paddingRight = (isset($options['padding'])) ? $options['padding'] : "4px";
        $escape = (isset($options['escape'])) ? true : false;
        $helper = (isset($options['helper'])) ? $options['helper'] : '';
        $action = '';
        if ($helper) {
            $value = $this->$helper($value);
        }
        if ($escape) {
            $value = htmlspecialchars($value);
        }
        if ($options['extra']) {
            $url = $options['extra']['url'];
            $target = (isset($options['extra']['target'])) ? 'target="_blank"' : '';
            $urlText = (isset($options['extra']['text'])) ? $options['extra']['text'] : 'text';
            $action = "<span><a style='text-decoration:none;border-style:none none dotted none;color:black;border-width:1px;' href='$url' $target>$urlText</a></span>";
        }
        $html = <<<HTMLTEXT
            <$container>
                    <span style="min-width:$minWidth;text-align:right;padding-right:$paddingRight;vertical-align:top;display:inline-block;font-weight:bolder;">$label</span>
                    <span style="display:inline-block;">$value</span>
                        $action
        </$container>
HTMLTEXT;
        return $html;
    }

    /**
     * 
     * @param mixed $value
     * @param string $currency USD
     * @return string
     */
    public function money($value, $currency = 'USD') {
        return "$currency $" . sprintf("%0.2f", $value);
    }

    /**
     * 
     * @param mixed $value
     * @param string $currency USD
     * @return string
     */
    public function moneyNoCent($value, $currency = 'USD') {

        return "$currency $" . sprintf("%1.0f", $value);
    }

    /**
     * 
     * @param mixed $value
     * @param string $currency USD
     * @return string
     */
    public function moneyWithComma($value, $currency = 'USD') {
        $newValue = number_format($value, 2, '.', ',');
        return "$currency $" . $newValue;
    }

    /**
     * 
     * @param mixed $value
     * @param string $currency USD
     * @return string
     */
    public function monthlyPrice($value, $currency = 'USD') {
        $newValue = number_format($value, 2, '.', ',');
        return "$currency $" . $newValue . "/month";
    }

    /**
     * 
     * @param string $html
     * @return string
     */
    public function nlbrText($html) {
        return nl2br($html);
    }

    /**
     * translate message to language specified by browser by 
     * $_SERVER['HTTP_ACCEPT_LANGUAGE'] field
     * @param string $message
     * @return string
     */
    public function translate($message) {
        $lang = getLanguage();
        $fileName = ROOT . DS . "public" . DS . "language" . DS . "{$lang}.php";
        if (file_exists($fileName)) {
            include $fileName;
            if (isset($lang) && !empty($lang[$message])) {
                return $lang[$message];
            }
        }
        return $message;
    }

    public $countryList = array
        (
        'AF' => 'Afghanistan',
        'AL' => 'Albania',
        'DZ' => 'Algeria',
        'AS' => 'American Samoa',
        'AD' => 'Andorra',
        'AO' => 'Angola',
        'AI' => 'Anguilla',
        'AQ' => 'Antarctica',
        'AG' => 'Antigua and Barbuda',
        'AR' => 'Argentina',
        'AM' => 'Armenia',
        'AW' => 'Aruba',
        'AU' => 'Australia',
        'AT' => 'Austria',
        'AZ' => 'Azerbaijan',
        'BS' => 'Bahamas',
        'BH' => 'Bahrain',
        'BD' => 'Bangladesh',
        'BB' => 'Barbados',
        'BY' => 'Belarus',
        'BE' => 'Belgium',
        'BZ' => 'Belize',
        'BJ' => 'Benin',
        'BM' => 'Bermuda',
        'BT' => 'Bhutan',
        'BO' => 'Bolivia',
        'BA' => 'Bosnia and Herzegovina',
        'BW' => 'Botswana',
        'BV' => 'Bouvet Island',
        'BR' => 'Brazil',
        'BQ' => 'British Antarctic Territory',
        'IO' => 'British Indian Ocean Territory',
        'VG' => 'British Virgin Islands',
        'BN' => 'Brunei',
        'BG' => 'Bulgaria',
        'BF' => 'Burkina Faso',
        'BI' => 'Burundi',
        'KH' => 'Cambodia',
        'CM' => 'Cameroon',
        'CA' => 'Canada',
        'CT' => 'Canton and Enderbury Islands',
        'CV' => 'Cape Verde',
        'KY' => 'Cayman Islands',
        'CF' => 'Central African Republic',
        'TD' => 'Chad',
        'CL' => 'Chile',
        'CN' => 'China',
        'CX' => 'Christmas Island',
        'CC' => 'Cocos Keeling Islands',
        'CO' => 'Colombia',
        'KM' => 'Comoros',
        'CG' => 'Congo - Brazzaville',
        'CD' => 'Congo - Kinshasa',
        'CK' => 'Cook Islands',
        'CR' => 'Costa Rica',
        'HR' => 'Croatia',
        'CU' => 'Cuba',
        'CY' => 'Cyprus',
        'CZ' => 'Czech Republic',
        'CI' => 'Côte d’Ivoire',
        'DK' => 'Denmark',
        'DJ' => 'Djibouti',
        'DM' => 'Dominica',
        'DO' => 'Dominican Republic',
        'NQ' => 'Dronning Maud Land',
        'DD' => 'East Germany',
        'EC' => 'Ecuador',
        'EG' => 'Egypt',
        'SV' => 'El Salvador',
        'GQ' => 'Equatorial Guinea',
        'ER' => 'Eritrea',
        'EE' => 'Estonia',
        'ET' => 'Ethiopia',
        'FK' => 'Falkland Islands',
        'FO' => 'Faroe Islands',
        'FJ' => 'Fiji',
        'FI' => 'Finland',
        'FR' => 'France',
        'GF' => 'French Guiana',
        'PF' => 'French Polynesia',
        'TF' => 'French Southern Territories',
        'FQ' => 'French Southern and Antarctic Territories',
        'GA' => 'Gabon',
        'GM' => 'Gambia',
        'GE' => 'Georgia',
        'DE' => 'Germany',
        'GH' => 'Ghana',
        'GI' => 'Gibraltar',
        'GR' => 'Greece',
        'GL' => 'Greenland',
        'GD' => 'Grenada',
        'GP' => 'Guadeloupe',
        'GU' => 'Guam',
        'GT' => 'Guatemala',
        'GG' => 'Guernsey',
        'GN' => 'Guinea',
        'GW' => 'Guinea-Bissau',
        'GY' => 'Guyana',
        'HT' => 'Haiti',
        'HM' => 'Heard Island and McDonald Islands',
        'HN' => 'Honduras',
        'HK' => 'Hong Kong SAR China',
        'HU' => 'Hungary',
        'IS' => 'Iceland',
        'IN' => 'India',
        'ID' => 'Indonesia',
        'IR' => 'Iran',
        'IQ' => 'Iraq',
        'IE' => 'Ireland',
        'IM' => 'Isle of Man',
        'IL' => 'Israel',
        'IT' => 'Italy',
        'JM' => 'Jamaica',
        'JP' => 'Japan',
        'JE' => 'Jersey',
        'JT' => 'Johnston Island',
        'JO' => 'Jordan',
        'KZ' => 'Kazakhstan',
        'KE' => 'Kenya',
        'KI' => 'Kiribati',
        'KW' => 'Kuwait',
        'KG' => 'Kyrgyzstan',
        'LA' => 'Laos',
        'LV' => 'Latvia',
        'LB' => 'Lebanon',
        'LS' => 'Lesotho',
        'LR' => 'Liberia',
        'LY' => 'Libya',
        'LI' => 'Liechtenstein',
        'LT' => 'Lithuania',
        'LU' => 'Luxembourg',
        'MO' => 'Macau SAR China',
        'MK' => 'Macedonia',
        'MG' => 'Madagascar',
        'MW' => 'Malawi',
        'MY' => 'Malaysia',
        'MV' => 'Maldives',
        'ML' => 'Mali',
        'MT' => 'Malta',
        'MH' => 'Marshall Islands',
        'MQ' => 'Martinique',
        'MR' => 'Mauritania',
        'MU' => 'Mauritius',
        'YT' => 'Mayotte',
        'FX' => 'Metropolitan France',
        'MX' => 'Mexico',
        'FM' => 'Micronesia',
        'MI' => 'Midway Islands',
        'MD' => 'Moldova',
        'MC' => 'Monaco',
        'MN' => 'Mongolia',
        'ME' => 'Montenegro',
        'MS' => 'Montserrat',
        'MA' => 'Morocco',
        'MZ' => 'Mozambique',
        'MM' => 'Myanmar Burma',
        'NA' => 'Namibia',
        'NR' => 'Nauru',
        'NP' => 'Nepal',
        'NL' => 'Netherlands',
        'AN' => 'Netherlands Antilles',
        'NT' => 'Neutral Zone',
        'NC' => 'New Caledonia',
        'NZ' => 'New Zealand',
        'NI' => 'Nicaragua',
        'NE' => 'Niger',
        'NG' => 'Nigeria',
        'NU' => 'Niue',
        'NF' => 'Norfolk Island',
        'KP' => 'North Korea',
        'VD' => 'North Vietnam',
        'MP' => 'Northern Mariana Islands',
        'NO' => 'Norway',
        'OM' => 'Oman',
        'PC' => 'Pacific Islands Trust Territory',
        'PK' => 'Pakistan',
        'PW' => 'Palau',
        'PS' => 'Palestinian Territories',
        'PA' => 'Panama',
        'PZ' => 'Panama Canal Zone',
        'PG' => 'Papua New Guinea',
        'PY' => 'Paraguay',
        'YD' => 'People\'s Democratic Republic of Yemen',
        'PE' => 'Peru',
        'PH' => 'Philippines',
        'PN' => 'Pitcairn Islands',
        'PL' => 'Poland',
        'PT' => 'Portugal',
        'PR' => 'Puerto Rico',
        'QA' => 'Qatar',
        'RO' => 'Romania',
        'RU' => 'Russia',
        'RW' => 'Rwanda',
        'RE' => 'Réunion',
        'BL' => 'Saint Barthélemy',
        'SH' => 'Saint Helena',
        'KN' => 'Saint Kitts and Nevis',
        'LC' => 'Saint Lucia',
        'MF' => 'Saint Martin',
        'PM' => 'Saint Pierre and Miquelon',
        'VC' => 'Saint Vincent and the Grenadines',
        'WS' => 'Samoa',
        'SM' => 'San Marino',
        'SA' => 'Saudi Arabia',
        'SN' => 'Senegal',
        'RS' => 'Serbia',
        'CS' => 'Serbia and Montenegro',
        'SC' => 'Seychelles',
        'SL' => 'Sierra Leone',
        'SG' => 'Singapore',
        'SK' => 'Slovakia',
        'SI' => 'Slovenia',
        'SB' => 'Solomon Islands',
        'SO' => 'Somalia',
        'ZA' => 'South Africa',
        'GS' => 'South Georgia and the South Sandwich Islands',
        'KR' => 'South Korea',
        'ES' => 'Spain',
        'LK' => 'Sri Lanka',
        'SD' => 'Sudan',
        'SR' => 'Suriname',
        'SJ' => 'Svalbard and Jan Mayen',
        'SZ' => 'Swaziland',
        'SE' => 'Sweden',
        'CH' => 'Switzerland',
        'SY' => 'Syria',
        'ST' => 'São Tomé and Príncipe',
        'TW' => 'Taiwan',
        'TJ' => 'Tajikistan',
        'TZ' => 'Tanzania',
        'TH' => 'Thailand',
        'TL' => 'Timor-Leste',
        'TG' => 'Togo',
        'TK' => 'Tokelau',
        'TO' => 'Tonga',
        'TT' => 'Trinidad and Tobago',
        'TN' => 'Tunisia',
        'TR' => 'Turkey',
        'TM' => 'Turkmenistan',
        'TC' => 'Turks and Caicos Islands',
        'TV' => 'Tuvalu',
        'UM' => 'U.S. Minor Outlying Islands',
        'PU' => 'U.S. Miscellaneous Pacific Islands',
        'VI' => 'U.S. Virgin Islands',
        'UG' => 'Uganda',
        'UA' => 'Ukraine',
        'SU' => 'Union of Soviet Socialist Republics',
        'AE' => 'United Arab Emirates',
        'GB' => 'United Kingdom',
        'US' => 'United States',
        'ZZ' => 'Unknown or Invalid Region',
        'UY' => 'Uruguay',
        'UZ' => 'Uzbekistan',
        'VU' => 'Vanuatu',
        'VA' => 'Vatican City',
        'VE' => 'Venezuela',
        'VN' => 'Vietnam',
        'WK' => 'Wake Island',
        'WF' => 'Wallis and Futuna',
        'EH' => 'Western Sahara',
        'YE' => 'Yemen',
        'ZM' => 'Zambia',
        'ZW' => 'Zimbabwe',
        'AX' => 'Åland Islands',
    );

}

?>
