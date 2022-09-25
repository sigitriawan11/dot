<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $with = ['kelas', 'hobby'];

    public function kelas(){
        return $this->belongsTo(Kelas::class);
    }

    public function hobby(){
        return $this->hasMany(Hobby::class);
    }
}
