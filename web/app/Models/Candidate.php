<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'strengths',
        'soft_skills',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'strengths' => 'array',
        'soft_skills' => 'array',
    ];

    /**
     * The wallet of the company
     */
    public function employments()
    {
        return $this->hasMany(Employment::class, 'candidate_id');
    }

    public function employmentsByCompany($companyId)
    {
        return $this->employments()->where('company_id', $companyId);
    }
}
