<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'category_id',
        'provider_id',
        'quantity',
        'price',
        'add_by_id',
    ];

    /**
     * Relation with category, one product belong only to one category
     *
     * @return mixed
     */
    public function category()
    {
        return $this->belongsto('App\Models\Category');
    }

    /**
     * Relation with provider, one product belong only to one provider
     *
     * @return mixed
     */
    public function provider()
    {
        return $this->belongsto('App\Models\Provider');
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
        $path = 'store/product/'.$this->getDatePath($this->created_at).'/'.$this->name.'/logo';
        $logo_path = $photo->store($path);
        $this->logo_path = $logo_path;
        $this->save();
    }
}
