<?php

namespace Gopay\GopayUi\Models;

use Illuminate\Database\Eloquent\Model;

class GopayForm extends Model
{
    protected $table = 'gopay_form';

    protected $fillable = [
        'reference',
        'amount',
        'currency',
        'phone',
        'payload',
        'signature',
    ];

    protected $casts = [];
}
