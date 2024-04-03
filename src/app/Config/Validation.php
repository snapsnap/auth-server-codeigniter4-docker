<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\StrictRules\FileRules;
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;

class Validation extends BaseConfig
{
    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var string[]
     */
    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public array $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    // --------------------------------------------------------------------
    // Rules
    // --------------------------------------------------------------------
    public $register = [
        'email' => 'valid_email',
        'pass' => 'min_length[6]|regex_match[^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{6,}$]',
        'company' => 'alpha_numeric_punct',
        'address' => 'alpha_numeric_punct',
        'phone' => 'min_length[9]|max_length[15]|regex_match[^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,7}$]',
    ];
        
   public $register_errors = [
       'email' => [
           'valid_email' => 'Email harus valid format',
        ],
        'pass' => [
            'min_length' => 'Password minimal 6 karakter',
            'regex_match' => 'Password hanya boleh mengandung angka, huruf, dan karakter yang valid ( @ ! $ % & * )'
        ],
        'company' => [
            'alpha_numeric_punct' => 'Nama Perusahaan hanya boleh mengandung angka, huruf, dan karakter yang valid (! $ % & *  - _ + = | : . )'
        ],
        'address' => [
            'alpha_numeric_punct' => 'Alamat hanya boleh mengandung angka, huruf, dan karakter yang valid (! $ % & *  - _ + = | : . )'
        ],
        'phone' => [
            'min_length' => 'No. Telephone minimal 9 angka',
            'max_length' => 'No. Telephone maximal 15 angka',
            'regex_match' => 'Format No. Telephone tidak valid'
        ],
    ];

    public $createApp = [
        'appName' => 'alpha_numeric_punct'
    ];

    public $createApp_errors = [
        'appName' => [
            'alpha_numeric_punct' => 'App Name hanya boleh mengandung angka, huruf, dan karakter yang valid'
        ]
    ];
}
