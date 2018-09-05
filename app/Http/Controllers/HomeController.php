<?php

namespace App\Http\Controllers;

use App\Http\Models\QuestionToTag;
use App\Http\Models\Zhuanji;
use Illuminate\Http\Request;
use App\Http\Models\Questions;
use App\Http\Models\Replies;
use App\Http\Models\Tag;
use Auth;
use App\Http\Requests\QuestionsRequest;

class HomeController extends Controller
{
    const REPLY_COUNT = 0;
    const VIEW_COUNT = 0;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
//    public function __construct()
//    {
//        $this->middleware('auth');
//    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function tag(){
        $tags = DB::table('tag')->get()->toArray();
        $array = $this->getTree($tags);
        $data = [];
        foreach ($array as $value){
            if ($value->level == 0){
                $data1[] = $value;
            }else{
                $data2[] = $value;
            }
        }
        foreach ($data1 as $v){
            foreach ($data2 as $vv){
                if ($vv->pid == $v->id){
                    $data[$v->id][] = $vv;
                }
            }
        }
        foreach ($data1 as $vvv){
            foreach ($data2 as $vvvv){

            }
        }
        return view('index',compact('data'));
    }

    function getTree($array, $pid =0, $level = 0){

        //声明静态数组,避免递归调用时,多次声明导致数组覆盖
        static $list = [];
        foreach ($array as $key => $value){
            //第一次遍历,找到父节点为根节点的节点 也就是pid=0的节点
            if ($value->pid == $pid){
                //父节点为根节点的节点,级别为0，也就是第一级
                $value->level = $level;
                //把数组放到list中
                $list[] = $value;
                //把这个节点从数组中移除,减少后续递归消耗
                unset($array[$key]);
                //开始递归,查找父ID为该节点ID的节点,级别则为原级别+1
                $this->getTree($array, $value->id, $level+1);
            }
        }
        return $list;
    }

    public function ask()
    {
        // hot wenda
        $data['questions'] = Questions::getHotQuestions();

        // 找你回答 questions
        $data['findHelps'] = Questions::findHelp();

        // 最新 replies
        $data['newReplies'] = Replies::newReplies();

        // 最新 question_reply
        $data['newQuestion'] = Questions::newQuestions();

        // 最新 tag
        $data['tags'] = Tag::newQuestionTag();

        //精选 zhuanji
        $data['zhuanji'] = Zhuanji::jxzj();






















        $data['userQuestion'] = Questions::personQuestions();

        dd($data['userQuestion']);

        return view('ask.index',$data);
    }

    public function create()
    {
        //Todo:: class
    }

    public function store(QuestionsRequest $request)
    {
        $data['content'] = $request->get('content');
        $data['tag_id'] = $request->get('tag_id');
        $data['user_id'] = Auth::id;
        $data['reply_count'] = self::REPLY_COUNT;
        $data['view_count'] = self::VIEW_COUNT;

        $result = Questions::saveQuestion($data);

        if ($result){
            return redirect('ask');
        }
    }

    public function tagShow($id)
    {
        $result = QuestionToTag::tagShow($id);

        dd($result);
    }
}
