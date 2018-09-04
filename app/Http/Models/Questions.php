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

    public static function newQuestions()
    {
        $newQuestions = self::select()->where('reply_count','>','0')
            ->orderBy('create_at','desc')
            ->limit(5)
            ->get()
            ->toArray();
        $replies = Replies::all()->toArray();

        foreach ($newQuestions as $k=>$newQuestion){
            foreach ($replies as $reply){
                if ($newQuestion['id'] == $reply['question_id']){
                    $newQuestions[$k]['reply'][] = $reply;
                }
            }
        }

        return $newQuestions;
    }

    public static function saveQuestion($data)
    {
        $questionId = self::insertGetId($data);
        $tags_ids = $data['tag_id'];
        $tags_ids = explode(',',$tags_ids);
        if (count($tags_ids) == 1){
            $first['tag_id'] = $data['tag_id'];
            $first['question_id'] = $questionId;
            $first['create_at'] = date('Y-m-d H:i:s');
            $first['updated_at'] = date('Y-m-d H:i:s');
            QuestionToTag::insert($first);
        }else{
            foreach ($tags_ids as $tags_id){
                $double['tag_id'] = $tags_id;
                $double['question_id'] = $questionId;
                $double['create_at'] = date('Y-m-d H:i:s');
                $double['updated_at'] = date('Y-m-d H:i:s');
                QuestionToTag::insert($double);
            }
        }
    }

    public static function tagShow($id)
    {
        $questions = self::select()->where('tag_id',$id)->get()->toArray();

        return $questions;
    }
}