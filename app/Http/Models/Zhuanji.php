<?php
/**
 * Created by PhpStorm.
 * User: administrator
 * Date: 2018/9/3
 * Time: 11:44
 */
namespace App\Http\Models;

use Arcanedev\Support\Database\Model;

class Zhuanji extends Model
{
    protected $table = 'zhuanji';

    public static function jxzj()
    {
        $zhuanji = self::all()->toArray();

        return $zhuanji;
    }
}