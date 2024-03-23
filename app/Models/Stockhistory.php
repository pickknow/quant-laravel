<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\AshareException;


class Stockhistory extends Model
{
    use HasFactory;

    public static $colmune_names = [
        'date', 'open', 'close', 'high', 'low', 'vol', 'amt', 'amplitude', 'quote_change', 'changes', 'ratio',
    ];
    public $fillable = [
        'date', 'open', 'close', 'high', 'low', 'vol', 'amt', 'amplitude', 'quote_change', 'changes', 'ratio'
    ];

    public static function preventDouble($date = null)
    {
        $date = $date ?: (new DateTime())->format('Y-m-d');
        $dateTime = new DateTime($date);
        $result =  self::where('date', '>=', $dateTime)->first();
        throw_if(
            $result,
            AshareException::class,
            "::Today's data already exist."
        );
        return $date;
    }
    public static function zipCreate($datas)
    {
        $res = array_map(fn ($value) => array_combine(self::$colmune_names, $value), $datas);
        self::insert($res);
    }
}
