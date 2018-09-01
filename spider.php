
<?php
/**
 * http://blog.csdn.net/will5451/article/details/78999995?%3E
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/1
 * Time: 22:51
 */
require './vendor/autoload.php';
use phpspider\core\phpspider;
use phpspider\core\requests;    //请求类
use phpspider\core\selector;    //选择器类
use phpspider\core\db;    //选择器类
use phpspider\core\log;    //选择器类

/* Do NOT delete this comment */
/* 不要删除这段注释 */

$configs = array(
    'name' => '爬取新闻',
    //'log_show' => true,
    //定义爬虫爬取哪些域名下的网页, 非域名下的url会被忽略以提高爬取速度
    'domains' => array(
        'www.ncnews.com.cn'    //写域名
    ),
    //定义爬虫的入口链接, 爬虫从这些链接开始爬取,同时这些链接也是监控爬虫所要监控的链接
    'scan_urls' => array(
        'http://www.ncnews.com.cn/xwzx/ncxw/twnc/'
    ),
    //定义内容页url的规则
    'content_url_regexes' => array(
        "http://www.ncnews.com.cn/xwzx/ncxw/twnc/index(_[0-9]{0,2})?.html"
    ),
    //爬虫爬取每个网页失败后尝试次数
    'max_try' => 5,
    //爬虫爬取数据导出
    'export' => array(
        'type' => 'db',
        'table'=> 'articles',    //表名
    ),
    'db_config' => array(
        'host'  => '192.168.0.30',
        'port'  => 3306,
        'user'  => 'root',    //mysql的账号
        'pass'  => '123456',               //mysql的密码
        'name'  => 'mylaravel',   //库名
    ),

    'fields' => array(
        //从列表页开始爬
        array(
            'name' => "lists",
            'selector' => "//div[contains(@id,'container')]//ul//li[contains(@class,'item')]",
            'required' => true,
            'repeated' => true  //写上是数组（抓的是整个列表页），不写是字符串（只抓第一个）
        ),
    ),
    //日志存放的位置
//    'log_file' => '/storage/logs/qiushibaike.log',
    //只记录 错误和调试日志
    'log_type' => 'error,debug,warn,error',
    //爬虫爬取网页所使用的浏览器类型.随机浏览器类型，用于破解防采集
    'user_agent' => array(
        "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36",
        "Mozilla/5.0 (iPhone; CPU iPhone OS 9_3_3 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13G34 Safari/601.1",
        "Mozilla/5.0 (Linux; U; Android 6.0.1;zh_cn; Le X820 Build/FEXCNFN5801507014S) AppleWebKit/537.36 (KHTML, like Gecko)Version/4.0 Chrome/49.0.0.0 Mobile Safari/537.36 EUI Browser/5.8.015S",
    ),
    //爬虫爬取网页所使用的伪IP。随机伪造IP，用于破解防采集
    'client_ip' => array(
        '192.168.0.2',
        '192.168.0.3',
        '192.168.0.4',
    ),


);
$spider = new phpspider($configs);

//爬虫初始化时调用, 用来指定一些爬取前的操作
$spider->on_start = function($spider)
{
    requests::set_header("Referer", "http://www.ncnews.com.cn/xwzx/ncxw/twnc/index.html");
};

//在爬取到入口url的内容之后, 添加新的url到待爬队列之前调用. 主要用来发现新的待爬url, 并且能给新发现的url附加数据（点此查看“url附加数据”实例解析）.
$spider->on_scan_page = function($page,$content,$spider){
    //列表页只采集3页。
    for($i=0;$i<3;$i++){
        if($i == 0){    //第一页
            $url = "http://www.ncnews.com.cn/xwzx/ncxw/twnc/index.html";
        }else{          //之后的n页
            $url = "http://www.ncnews.com.cn/xwzx/ncxw/twnc/index_{$i}.html";
        }

        $options = [
            'method' => 'get',
            'params' => [
                'page' => $i
            ],
        ];

        $spider->add_url($url,$options);    //添加新的url到待爬队列
    }
};
/**
 * 对匹配后的字段field进行回调处理
 * @param $filename
 * @param $data
 * @param $page
 * @return array
 */
$spider->on_extract_field = function($filename,$data,$page){
    $arr = [];
    //处理抽取到的fields中name == lists的数据
    if($filename == 'lists'){
        if(is_array($data)){
            foreach($data as $k=>$v){
                $img = selector::select($v,"//img");
                //如果该新闻没有图片，就删除这条数据
                if(empty($img)){
                    unset($data[$k]);
                }else{
                    $url = "http://www.ncnews.com.cn/xwzx/ncxw/twnc";
                    $title = trim(selector::select($v,"//h3//a"));    //抓列表页的标题
                    //抓列表页的图片
                    if(substr(selector::select($v,"//img"),0,1)){
                        $title_imgs = selector::select($v,"//img");
                    }else{
                        $title_imgs = $url . ltrim(selector::select($v,"//img"),'.');
                    }

                    $title_desc = trim(selector::select($v,"//h5"));    //抓列表页的新闻简介
                    //抓文章，跳转到内容页
                    $p = '/<h3><a[^<>]+href * \= *[\"\']?([^\'\"\+]).*?/i';
                    $title_url = selector::select($v,$p,'regex');
                    if(substr($title_url,0,1) == 'h'){
                        $title_link = $title_url;
                    }else{
                        $title_link = $url . ltrim($title_url,'.');
                    }

                    $title_time = strip_tags(selector::select($v,"//h6"));  //抓列表页的时间

                    //组装数据
                    $arr[$k] = [
                        'title' => $title,
                        'title_imgs' => $title_imgs,
                        'title_desc' => $title_desc,
                        'title_link' => $title_link,    //前往内容页的链接
                        'title_time' => $title_time,
                    ];
                }
            }
        }
    }
    return $arr;
};

//入库操作
$spider->on_extract_page = function($page,$data){
//    echo "<pre>";
//    var_dump($data);
//    die;
    //处理哪个数据
    if(isset($data['lists'])){
        foreach($data['lists'] as $v){
            $arr = [
                'title' => trim($v['title']),
                'title_imgs' => urlencode($v['title_imgs']),
                'title_desc' => $v['title_desc'],
                'title_link' => urlencode($v['title_link']),
                'title_time' => $v['title_time']
            ];

            //标题重复就不入库
            $sql  = "select count(*) as `count` from `pachong` where `title`".$v['title'];
            $row = db::get_one($sql);
            if(!$row['count']){
                db::insert('pachong',$arr);
            }
        }

        $data = $arr;
    }
    return $data;
};
$spider->start();
