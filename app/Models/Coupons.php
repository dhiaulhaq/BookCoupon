<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupons extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'discount', 'expiry_date', 'is_active'];

    public static function generateCode($prefix)
    {
        $randomNumber = mt_rand(100, 999);
        return strtoupper($prefix) . $randomNumber;
    }
}
