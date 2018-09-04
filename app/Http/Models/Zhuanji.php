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
        $zhuanjis = self::select()->where('pid',0)->get()->toArray();
        return $zhuanjis;
    }

    /**
     * view_count 增加1
     * 调用方式 $zhuanji = new Zhuanji();
                $zhuanji->getCount($id);
     * @param $id 专辑id
     */
    public function getCount($id)
    {
        $count = self::where('id',$id)->value('view_count');
        self::where('id',$id)->update(['view_count'=>$count+1]);
    }
}