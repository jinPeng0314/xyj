<?php
/**
 * Created by PhpStorm.
 * User: administrator
 * Date: 2018/9/3
 * Time: 16:41
 */
namespace App\Http\Models;

use Arcanedev\Support\Database\Model;

class QuestionToTag extends Model
{
    protected $table = 'question_to_tag';

    public static function tagShow($id)
    {
        $result = self::select()->where('tag_id',$id)->get()->toArray();

        $questionsIds = array_pluck($result,'question_id');

        $questions = Questions::all()->toArray();
        $data = [];
        foreach ($questionsIds as $questionsId){
            foreach ($questions as $question){
                if ($questionsId == $question['id']){
                    $data[] = $question;
                }
            }
        }

        $replies = Replies::all()->toArray();
        foreach ($data as $k=>$value){
            foreach($replies as $reply){
                if ($value['id'] == $reply['question_id']){
                    $data[$k]['reply'][] = $reply;
                }
            }
        }

        $tags = Tag::all()->toArray();
        foreach ($data as $k=>$value){
            foreach ($tags as $tag){
                if ($value['tag_id'] == $tag['id']){
                    $data[$k]['tagName'] = $tag['name'];
                }
            }
        }
        return $data;
    }
}