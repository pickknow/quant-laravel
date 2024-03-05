<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\AshareException;

class Stock extends Model
{
    use HasFactory;
    public static $colmune_names = [
        'fdv', 'mkt_cap', 'industry', 'up_time', 'code', 'name', 'all_share', 'current_share',
    ];
    protected $fillable = [
        'fdv', 'mkt_cap', 'industry', 'up_time', 'code', 'name', 'all_share', 'current_share',
    ];

    public static function zipCreate($datas)
    {
        self::preventDouble();
        $res = array_map(fn ($value) => array_combine(self::$colmune_names, $value), $datas);
        self::insert($res);
    }

  public static function preventDouble($data = null)
    {
        $result =  self::first();
        throw_if(
            $result,
            AshareException::class,
            "::There already exist. Don't fetch again."
        );
        return $data;
    }
}
