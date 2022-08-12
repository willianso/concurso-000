<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cidade;

class CidadeController extends Controller
{
    public function index($estado_id = null)
    {
        $result = $estado_id ? Cidade::where('estado_id', $estado_id)->get() : Cidade::all();

        return json_encode($result);
    }
}
