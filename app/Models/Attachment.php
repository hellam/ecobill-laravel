<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Attachment
 *
 * @property int $id
 * @property string $description
 * @property int $trans_no
 * @property Carbon|null $trx_date
 * @property string $trx_type
 * @property string $file_name
 * @property int $file_size
 * @property string $file_type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Attachment extends Model
{
	protected $table = 'attachments';

	protected $casts = [
		'trans_no' => 'int',
		'file_size' => 'int'
	];

	protected $dates = [
		'trx_date'
	];

	protected $fillable = [
		'description',
		'trans_no',
		'trx_date',
		'trx_type',
		'file_name',
		'file_size',
		'file_type'
	];
}
