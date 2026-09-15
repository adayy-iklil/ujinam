<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Major extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
    ];

    public function schoolClasses(): HasMany
    {
        return $this->hasMany(SchoolClass::class, 'major_id');
    }
}
