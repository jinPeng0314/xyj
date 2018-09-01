<?php
/**
 * Created by PhpStorm.
 * User: administrator
 * Date: 2018/9/1
 * Time: 9:51
 */
namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Questions extends Model
{
    protected $table = 'question';

    public static function getHotQuestions()
    {
        $data = self::select()->where('reply_count','>','0')
                    ->orderBy('reply_count','desc')
                    ->limit(5)
                    ->get()
                    ->toArray();

        return $data;
    }

    public static function findHelp()
    {
        $data = self::select()->where('reply_count',0)
                    ->orderBy('create_at','desc')
                    ->limit(8)
                    ->get()
                    ->toArray();

        return $data;
    }

    public static function newQuestions($ids)
    {
        $questions = [];
        foreach ($ids as $id){
            $questions[] = self::select()->where('id',$id)->get()->toArray();
        }

        return $questions;
    }

    public static function saveQuestion($data)
    {
        $result = self::insert($data);

        return $result;
    }

    public static function tagShow($id)
    {
        $questions = self::select()->where('tag_id',$id)->get()->toArray();
        

        return $questions;
    }
}