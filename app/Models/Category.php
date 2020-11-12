<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'add_by_id',
    ];

    /**
     * relation user, category can be created only by one user
     *
     * @return mixed
     */
    public function User()
    {
        return $this->belongsto('App\Models\User', 'add_by_id');
    }

    /**
     * Relation with products, one category can has many product
     *
     * @return mixed
     */
    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }

    /**
     * Relation with users, category can be added by only one user
     *
     * @return mixed
     */
    public function addBy()
    {
        return $this->belongsto('App\Models\User', 'add_by_id');
    }
}
