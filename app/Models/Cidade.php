<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cidade extends Model
{
	protected $table = 'cidade';
	
	protected $fillable = [
	    'nome',
	    'estado_id',
	];
}
