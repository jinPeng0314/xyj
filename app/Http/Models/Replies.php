<?php
/**
 * Created by PhpStorm.
 * User: administrator
 * Date: 2018/9/1
 * Time: 9:51
 */
namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Replies extends Model
{
    protected $table = 'replies';

    public static function newReplies()
    {
        $newReplies = self::select()->orderBy('create_at','desc')
                    ->limit(5)
                    ->get()
                    ->toArray();

        $questions = Questions::all()->toArray();

        foreach ($newReplies as $k=>$newReply){
            foreach ($questions as $question){
                if ($question['id'] == $newReply['question_id']){
                    $newReplies[$k]['question'] = $question;
                }
            }
        }
        return $newReplies;
    }

}