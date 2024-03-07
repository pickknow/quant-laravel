<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stockhistory extends Model
{
    use HasFactory;

    public static $colmune_names = [
        'date', 'open', 'close', 'high', 'low', 'vol', 'amt', 'amplitude', 'quote_change', 'changes', 'ratio'
    ];
    public static $fillable = [
        'date', 'open', 'close', 'high', 'low', 'vol', 'amt', 'amplitude', 'quote_change', 'changes', 'ratio'
    ];
}
