<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Estado;

class EstadoController extends Controller
{
    public function index()
    {
        return json_encode(Estado::orderBy('nome')->get());
    }
}
