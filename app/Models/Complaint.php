<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [

        'ticket_number',

        'platform',

        'reporter_name',

        'reporter_contact',

        'message',

        'category_id',

        'sub_category_id',

        'opd_id',

        'status',

    ];

    public function category()
    {
        return $this->belongsTo(
            Category::class
        );
    }

    public function subCategory()
    {
        return $this->belongsTo(
            SubCategory::class
        );
    }

    public function opd()
    {
        return $this->belongsTo(
            Opd::class
        );
    }

    public function aiClassification()
    {
        return $this->hasOne(
            AIClassification::class
        );
    }
}
