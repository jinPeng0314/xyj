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
        $hotQuestions = self::select()->where('reply_count','>','0')
                    ->orderBy('reply_count','desc')
                    ->limit(5)
                    ->get()
                    ->toArray();
        $replies = Replies::all()->toArray();

        foreach ($hotQuestions as $k=>$hotQuestion){
            foreach ($replies as $reply){
                if ($hotQuestion['id'] == $reply['question_id']){
                    $hotQuestions[$k]['reply'][] = $reply;
                }
            }
        }
        return $hotQuestions;
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