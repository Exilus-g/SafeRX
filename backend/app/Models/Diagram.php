<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagram extends Model
{
    /**
     * A model representing a data entry with fillable fields 'user_id' and 'json_data'.
     *
     * This model has a relationship where it belongs to a User and has many Node instances.
     *
     * @property int $user_id
     * @property array $json_data
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'json_data'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function nodes()
    {
        return $this->hasMany(Node::class);
    }
}
