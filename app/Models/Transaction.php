<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable=[
        'nofaktur',
        'tanggal',
        'nama',
        'gender_id',
        'phone',
        'saldo',
        'address',
    ];
    public function gender()
    {
        return $this->belongsTo(Gender::class,'gender_id','id');
    }
    public function details()
    {
        return $this->hasMany(Detailtransaction::class);
    }
}
