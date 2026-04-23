<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CursoModel;

class HomeController extends BaseController
{
    public function index(): string
    {
        $cursoModel = new CursoModel();

        if (method_exists($cursoModel, 'obtenerCursosCompletos')) {
            $cursos = $cursoModel->obtenerCursosCompletos();
        } else {
            $cursos = $cursoModel->findAll();
        }

        return view('Inicio/Inicio', [
            'title'  => 'Inicio',
            'cursos' => $cursos,
        ]);
    }
}