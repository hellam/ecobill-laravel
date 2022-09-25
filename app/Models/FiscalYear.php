<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class FiscalYear
 *
 * @property int $id
 * @property Carbon $begin
 * @property Carbon $end
 * @property string $client_ref
 * @property bool $closed
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class FiscalYear extends Model
{
	protected $table = 'fiscal_year';

	protected $casts = [
		'closed' => 'bool'
	];

	protected $dates = [
		'begin',
		'end'
	];

	protected $fillable = [
		'begin',
		'end',
		'client_ref',
		'closed'
	];
}
