<?php

namespace App\Models;

use DateTime;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Industry extends Model
{
    use HasFactory;
    public $timestamps = false;
    public static $colmune_names = [
        'rank', 'name', 'code', 'price', 'up_price', 'up_by', 'tmc', 'turnover_rate',
        'ups', 'downs', 'leader_stock', 'leader_by'
    ];
    protected $fillable = [
        'rank', 'name', 'code', 'price', 'up_price', 'up_by', 'tmc', 'turnover_rate',
        'ups', 'downs', 'leader_stock', 'leader_by'
    ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->created_at = now();
        });
    }


    public function setCreatedAtAttribute($value)
    {
        $this->attributes['created_at'] = now();
    }


    public static function zipCreate($datas)
    {
        $created_at = now();
        $res = array_map(function ($value) use ($created_at) {
            $ss =  array_combine(self::$colmune_names, $value);
            $ss['created_at']= $created_at;
            return $ss;
        }, $datas);
        self::insert($res);
        dump('Records have been saved.');
    }
}
