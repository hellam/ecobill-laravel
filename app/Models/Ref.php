<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Ref
 * 
 * @property int $id
 * @property string $type
 * @property string $reference
 * @property string|null $client_ref
 *
 * @package App\Models
 */
class Ref extends Model
{
	protected $table = 'refs';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int'
	];

	protected $fillable = [
		'reference',
		'client_ref'
	];
}
