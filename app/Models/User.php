<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Carbon;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role_id',
        'is_enabled',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Relation with role user can has only one role
     *
     * @return mixed
     */
    public function role()
    {
        return $this->belongsTo('App\Models\Role');
    }

    /**
     * Relation with provider user can create many providers
     *
     * @return mixed
     */
    public function providers()
    {
        return $this->hasMany('App\Models\Provider', 'add_by_id');
    }

    /**
     * Relation with category user can create many categories
     *
     * @return mixed
     */
    public function categories()
    {
        return $this->hasMany('App\Models\Category', 'add_by_id');
    }

    /**
     * Relation with product user can create many products
     *
     * @return mixed
     */
    public function products()
    {
        return $this->hasMany('App\Models\Product', 'add_by_id');
    }

    /**
     * Generation path using year/month/day
     *
     * @param $createdAt
     * @return string
     */
    private function getDatePath($createdAt)
    {
        $year = Carbon::createFromFormat('Y-m-d H:i:s', $createdAt)->year;
        $month = Carbon::createFromFormat('Y-m-d H:i:s', $createdAt)->month;
        $day = Carbon::createFromFormat('Y-m-d H:i:s', $createdAt)->day;

        return $year.'/'.$month.'/'.$day;
    }

    /**
     * Put photo in specific path
     *
     * @param $photo
     */
    public function uploadPhoto($photo)
    {
        $path = 'store/user/'.$this->getDatePath($this->created_at).'/'.$this->username.'/photo';
        $photo_path = $photo->store($path);
        $this->photo_path = $photo_path;
        $this->save();
    }
}
