<?php
/**
 * Created by PhpStorm.
 * User: administrator
 * Date: 2018/9/4
 * Time: 16:47
 */
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'content' => 'required',
            'tag_id'  => 'required'
        ];
    }
}