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

    public static function getHotReplies($ids)
    {
        $replies = [];
        foreach ($ids as $id){
            $replies[] = self::select()->where('question_id',$id)->get()->toArray();
        }

        return $replies;
    }

    public static function newReplies()
    {
        $data = self::select()->orderBy('create_at','desc')
                    ->limit(5)
                    ->get()
                    ->toArray();

        return $data;
    }

}