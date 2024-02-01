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
        'ups', 'downs', 'leader_stock', 'leader_by', 'created_at'
    ];



    public function setCreatedAtAttribute($value) { 
        $this->attributes['created_at'] = new DateTime();
    }


    public static function zipCreate($datas) {
        $res = array_map(function($value) {
            return array_combine(self::$colmune_names, $value);
        }, $datas);
        self::insert($res);
        dump('all done');
    }

}
