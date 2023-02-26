<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;
    protected $table = 'kategori';
	protected $guarded = [];
	public function childs(){
		return $this->hasMany(Kategori::class, 'induk', 'id');
	}
    public function parent()
    {
        return $this->belongsTo(Kategori::class, 'induk', 'id');
    }
}
