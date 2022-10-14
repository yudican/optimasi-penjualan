<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    //use Uuid;
    use HasFactory;
    protected $table = 'pengaturan';
    //public $incrementing = false;

    protected $fillable = ['code', 'value'];

    protected $dates = [];
}
