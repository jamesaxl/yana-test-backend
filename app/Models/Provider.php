<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Provider extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'add_by_id',
    ];

    /**
     * Relation with provider, one provider can has many product
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
    public function uploadLogo($photo)
    {
        $path = 'store/provider/'.$this->getDatePath($this->created_at).'/'.$this->name.'/logo';
        $logo_path = $photo->store($path);
        $this->logo_path = $logo_path;
        $this->save();
    }
}
