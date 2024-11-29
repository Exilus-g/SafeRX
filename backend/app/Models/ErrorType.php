<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErrorType extends Model
{
    /**
     * Represents an ErrorType model that is associated with a User and can have multiple Errors.
     *
     * This model is fillable with 'user_id' and 'type_name'.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type_name'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function errors()
    {
        return $this->belongsToMany(Error::class, 'error_type_error', 'error_type_id', 'error_id');
    }
}
