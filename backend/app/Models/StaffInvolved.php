<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffInvolved extends Model
{
    /**
     * A model representing a staff member.
     *
     * This model is associated with the "staff" table in the database.
     * It is fillable with 'user_id' and 'staff_name' attributes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'staff_name'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
