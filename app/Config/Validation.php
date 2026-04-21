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
        'list' => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];


    // --------------------------------------------------------------------
    // Rules Register Person
    // --------------------------------------------------------------------
// app/Config/Validation.php
    public array $userRegister = [
        'persona_Nombre' => [
            'label' => 'Nombre',
            'rules' => 'required|min_length[2]|max_length[50]',
            'errors' => [
                'required' => 'El campo {field} es obligatorio.',
                'min_length' => 'El campo {field} debe tener al menos {param} caracteres.',
                'max_length' => 'El campo {field} no debe exceder {param} caracteres.',
            ],
        ],
        'persona_ApellidoPaterno' => [
            'label' => 'Apellido Paterno',
            'rules' => 'required|min_length[2]|max_length[50]',
            'errors' => [
                'required' => 'El campo {field} es obligatorio.',
                'min_length' => 'El campo {field} debe tener al menos {param} caracteres.',
                'max_length' => 'El campo {field} no debe exceder {param} caracteres.',
            ],
        ],
        'persona_ApellidoMaterno' => [
            'label' => 'Apellido Materno',
            'rules' => 'permit_empty|max_length[50]',
            'errors' => [
                'max_length' => 'El campo {field} no debe exceder {param} caracteres.',
            ],
        ],
        'persona_FechaNacimiento' => [
            'label' => 'Fecha de nacimiento',
            'rules' => 'required|valid_date[Y-m-d]',
            'errors' => [
                'required' => 'El campo {field} es obligatorio.',
                'valid_date' => 'El campo {field} no tiene un formato válido (YYYY-MM-DD).',
            ],
        ],
        'persona_Correo' => [
            'label' => 'Correo',
            'rules' => 'required|valid_email|max_length[100]',
            'errors' => [
                'required' => 'El campo {field} es obligatorio.',
                'valid_email' => 'El {field} no es válido.',
                'max_length' => 'El {field} no debe exceder {param} caracteres.',
            ],
        ],
        'persona_telefono' => [
            'label' => 'Teléfono',
            'rules' => 'required|max_length[100]',
            'errors' => [
                'required' => 'El campo {field} es obligatorio.',
                'max_length' => 'El {field} no debe exceder {param} caracteres.',
            ],
        ],
    ];

   public array $roles = [
    'rolesId' => [              
        'rules' => 'permit_empty|integer'
    ],

    'roles_Valor' => [
        'rules'  => 'required|min_length[3]|max_length[50]|is_unique[tbl_cat_roles.roles_Valor,rolesId,{rolesId}]',
        'errors' => [
            'required'  => 'El nombre del rol es obligatorio.',
            'is_unique' => 'Ya existe un rol con ese nombre.',
        ],
    ],

    'roles_Activo' => [
        'rules' => 'permit_empty|in_list[0,1]',
    ],
];



    // --------------------------------------------------------------------
    // Rules
    // --------------------------------------------------------------------
}
