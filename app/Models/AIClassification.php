<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AIClassification extends Model
{
    use HasFactory;

    protected $table =
        'ai_classifications';

    protected $fillable = [

        'notification_id',

        'suggested_category',

        'suggested_sub_category',

        'suggested_opds',

        'priority',

        'confidence',

        'reasoning',

    ];

    protected $casts = [

        'suggested_opds' => 'array',

    ];

    public function notification()
    {
        return $this->belongsTo(
            Notification::class
        );
    }
}