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
     * @var list<string>
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
    public $doseveSucursales = [
        'nombre' => [
            'rules' => 'required|min_length[2]|max_length[50]|alpha_space',
            'errors' => [
                'required' => 'El campo Nombre no acepta vacíos',
                'min_length' => 'El campo Nombre debe tener mínimo 2 letras',
                'max_length' => 'El campo Nombre no debe ser mayor de 50 letras',
                'alpha_space' => 'El campo Nombre debe contener solo letras y espacios'
            ]
        ],
        'direccion' => [
            'rules' => 'required|min_length[2]|max_length[50]',
            'errors' => [
                'required' => 'El campo Dirección no acepta vacíos',
                'min_length' => 'El campo Dirección debe tener mínimo 2 letras',
                'max_length' => 'El campo Dirección no debe ser mayor de 50 letras'
            ]
        ],

        'estado' => [
            'rules' => 'required|integer',
            'errors' => [
                'required' => 'El campo Estado no acepta vacíos',
                'integer' => 'El campo Estado debe contener solo números'
            ]
        ],
    ];    
}
