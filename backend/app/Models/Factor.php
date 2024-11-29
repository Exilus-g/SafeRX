<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factor extends Model
{
    /**
     * A Factor model represents a factor_name associated with a user.
     *
     * This model is associated with the User model using a belongsTo relationship.
     *
     * @property int $user_id
     * @property string $factor_name
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'factor_name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
