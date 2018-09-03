<?php
/**
 * Created by PhpStorm.
 * User: administrator
 * Date: 2018/9/1
 * Time: 15:47
 */
namespace App\Http\Models;

use Arcanedev\Support\Database\Model;

class Tag extends Model
{
    protected $table = 'tag';

    public static function newQuestionTag()
    {
        $questions = Questions::select()->where('reply_count','>',0)->orderBy('create_at','desc')->limit(5)->get()->toArray();
        $replies = Replies::all()->toArray();
        $tagsIds = [];
        foreach ($questions as $k=>$question){
            foreach ($replies as $reply){
                if ($question['id'] == $reply['question_id']){
                    $tagsIds[] = $question['tag_id'];
                }
            }
        }
        $tagsIds = array_unique($tagsIds); //去除重复id
        $tags = Tag::select()->where('pid','<>',0)->get()->toArray();
        $tagsName = [];
        foreach ($tags as $tag){
            foreach ($tagsIds as $tagsId){
                if ($tag['id'] == $tagsId){
                    $tagsName[$tag['id']][] = $tag['name'];
                }
            }
        }
        return $tagsName;
    }
}