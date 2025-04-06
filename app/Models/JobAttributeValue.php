<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class JobAttributeValue extends Model
{
    use HasFactory;

    protected $fillable = ['job_listing_id', 'attribute_id', 'value'];

    public function jobListing()
    {
        return $this->belongsTo(JobListing::class);
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }
}
