<?php

namespace Wymanliu01\app\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Image
 */
class Image extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'source_id',
        'source_type',
        'column_name',
        'url',
    ];
}
