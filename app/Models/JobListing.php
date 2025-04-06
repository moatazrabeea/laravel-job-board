<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobListing extends Model
{
    use HasFactory;

    protected $table = 'job_listings';

    protected $fillable = [
        'title', 'description', 'company_name', 'salary_min', 'salary_max',
        'is_remote', 'job_type', 'status', 'published_at'
    ];

    protected $casts = [
        'is_remote' => 'boolean',
        'published_at' => 'datetime',
    ];

// Relationships
    public function languages()
    {
        return $this->belongsToMany(Language::class, 'job_listing_language');
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class, 'job_listing_location');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'job_listing_category');
    }
    public function attributes()
    {
    return $this->belongsToMany(Attribute::class)
                ->withPivot('value')
                ->withTimestamps();
    }

}
