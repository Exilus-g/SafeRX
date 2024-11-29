<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Error extends Model
{
    use HasFactory;

    protected $fillable = [
        'error_date',
        'report_date',
        'patient_name',
        'date_of_birth',
        'area',
        'folder',
        'description',
        'node_id',
        'place_id',
        'staff_involved_id',
        'factor_id',
        'error_category_id',
        'user_id'
    ];

    public function node()
    {
        return $this->belongsTo(Node::class);
    }

    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    public function staffInvolved()
    {
        return $this->belongsTo(StaffInvolved::class);
    }

    public function factor()
    {
        return $this->belongsTo(Factor::class);
    }

    public function errorCategory()
    {
        return $this->belongsTo(ErrorCategory::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function errorTypes()
    {
        return $this->belongsToMany(ErrorType::class, 'error_type_error', 'error_id', 'error_type_id');
    }
}
