<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dataset extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['firstname', 'lastname', 'email', 'birthDate'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }
}
