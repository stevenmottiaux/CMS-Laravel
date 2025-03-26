<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submenu extends Model
{
    public $timestamps = false;

	protected $fillable = [
		'label',
		'link',
		'order',
	];
}
