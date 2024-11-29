<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    /**
     * A Place model represents a place with the specified fillable attributes.
     *
     * @property int $user_id
     * @property string $place_name
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'place_name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
