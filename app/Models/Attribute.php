<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'label', 'type', 'options'];

    protected $casts = [
        'options' => 'array',
    ];

    public function values()
    {
        return $this->hasMany(JobAttributeValue::class);
    }

        public function jobListings()
    {
        return $this->belongsToMany(JobListing::class)
            ->withPivot('value')
            ->withTimestamps();
    }

}
