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

    public static $name_map =  [
        "股票代码"  => "code",
        "股票简称"  => "name",
        "总股本"  => 'all_share',
        "流通股"  => 'current_share',
        "总市值"  => 'fdv',
        "流通市值"  => 'mkt_cap',
        "行业"  => "industry",
        "上市时间"  => 'up_time'
    ];

    public static function zipCreate($datas)
    {
        self::preventDouble();
        $res = array_map(fn ($value) => array_combine(self::$colmune_names, $value), $datas);
        self::insert($res);
    }

    public static function zipOneCreate($value)
    {
        // self::preventDouble();
        $res = array_reduce($value, fn ($p, $n) => [...$p, self::$name_map[$n[0]] => $n[1]], []);
        self::create($res);
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
