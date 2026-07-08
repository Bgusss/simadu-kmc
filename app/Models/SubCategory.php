<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubCategory extends Model
{
    use HasFactory;

    protected $fillable = [

        'category_id',

        'opd_id',

        'name'

    ];

    public function category()
    {
        return $this->belongsTo(
            Category::class
        );
    }

    public function opd()
    {
        return $this->belongsTo(
            Opd::class
        );
    }
}