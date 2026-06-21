<?php

namespace Gopay\GopayUi\Models;

use Illuminate\Database\Eloquent\Model;

class Gopay extends Model
{
    protected $table = 'gopay';
    public $timestamps = false;

    protected $casts = [
        'date' => 'datetime'
    ];

    protected $fillable = [
        'issaved',
        'isfailed',
        'myref',
        'ref',
        'paydata',
        'date',
        'save_error',
        'environment'
    ];
}
