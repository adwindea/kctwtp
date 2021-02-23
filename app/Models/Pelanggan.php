<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pelanggan extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'idpel',
        'no_meter',
        'nama',
        'alamat',
        'tarif',
        'daya',
        'krn_lama',
        'vkrn_lama',
        'krn',
        'vkrn',
        'kct1a',
        'kct1b',
        'kct2a',
        'kct2b',
        'pic'
    ];


    use HasFactory;
    use SoftDeletes;
}
