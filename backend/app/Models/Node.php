<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    /**
     * A class representing a Process model.
     *
     * This class defines the attributes that are fillable, relationships with other models, and uses a factory.
     *
     * @property int $user_id
     * @property string $process
     *
     * @method \App\Models\User diagram()
     * @method \Illuminate\Database\Eloquent\Collection errors()
     *
     * @return void
     */
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'process'
    ];

    public function errors()
    {
        return $this->hasMany(Error::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
