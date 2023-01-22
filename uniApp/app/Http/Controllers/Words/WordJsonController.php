<?php

namespace App\Http\Controllers\Words;

use App\Http\Controllers\Auth\WxAuthorizationsController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\JsonController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;


class WordJsonController extends Controller
{
    use JsonController;

    //

    /**
     * 书的json库添加
     * 文件json名与图片名字对应
     * 格式保持一致
     * @return string
     */
    public function add(Request $request)
    {

        //获取文件目录下json文件
        $fileName = glob('../storage/app/wordBook/*.json');
        if (!$fileName) return $this->jsonResponse('501', 'glob返回文件名错误', null);
        foreach ($fileName as $index => $filename) {
            $handle = @fopen($filename, "r",);
            if ($handle) {
                $i = 0;
                $arr = [];
                while (($buffer = fgets($handle, 99999)) !== false) {
//                $arr [] = json_decode(($buffer));
                    //$buffer type: string addslashes: / 转义 '/'
                    $arr [] = "'" . addslashes($buffer) . "'";
                    //总行数
                    $i++;
                }
                if (!feof($handle)) {
                    echo "Error: unexpected fgets() fail\n";
                }
                fclose($handle);
            }
            $details = trim(str_replace("'", '', $arr[$i - 1]));
            $person = trim(str_replace("'", '', $arr[$i - 2]));

            //存入数据库
            $name = substr($filename, 0, -5);
            $name = substr($name, 24);

            //存入 书库 word_json
            $url = asset("word/" . $name . ".jpg");
            $result = DB::table('words_json')->updateOrInsert(
                ['name' => $name],
                ['name' => $name,
                    'pic' => $url,
                    'details' => $details,
                    'person' => (int)$person,
                    'count' => $i - 2]
            );
            //已存文件
            if (DB::table($name)->count() == 0) {
                foreach ($arr as $index => $value) {
                    //json 文件 最后两行不存入数据库
                    if ($index == $i - 1 || $index == $i - 2) break;
                    $result = DB::insert("insert into " . $name . " (json) values ($value)");
                }
                if (!$result) return $this->jsonResponse('501', 'fail', $result);
            }
        }
        return $this->jsonResponse('200', 'success', null);
    }

    /**
     * 单词索引添加
     * @return JsonResponse
     */
    public function sort()
    {
        $books = DB::table('words_json')->get('name');
        foreach ($books as $value){
            $words = DB::table($value->name)->get(['id', 'json']);
            foreach ($words as $word){
                $words = $word->json;
                $uid = $word->id;
                $words = json_decode($words);
                $book = $words->bookId;
                $word = $words->headWord;
                $result = DB::table('words_sort')->updateOrInsert(
                    ['word' => $word, 'book' => $book],
                    ['word' => $word, 'book' => $book, 'uid' => $uid]
                );
            }
        }
        return $this->jsonResponse('200', 'success', null);
    }

    /**
     * 单词查询
     * searchWord
     * @param Request $request
     * @return JsonResponse
     */
    public function searchWord(Request $request){
        $word = $request->get('word');
        $result = DB::table('words_sort')->limit(10)->where('word','like','%'.$word.'%')->get();
        $result_list = [];
        foreach ($result as $value){
            $uid = $value->uid;
            $book = $value->book;
            $book = strtolower($book);
            $wordJson = DB::table($book)->where('id', $uid)->first();
            $word = $wordJson->json;
            $word = json_decode($word);
            array_push($result_list, $word);
        }
        return $this->jsonResponse('200','success', $result_list);
    }

    /**
     * 单词库添加 api
     * @return string
     */
    public
    function get()
    {

        return '禁止访问';
        $filename = '../storage/app/wordBook/kaoyan_3.json';
        $handle = @fopen($filename, "r",);
        if ($handle) {
            while (($buffer = fgets($handle, 4096)) !== false) {
                $arr [] = json_decode(($buffer));

            }
            if (!feof($handle)) {
                echo "Error: unexpected fgets() fail\n";
            }
            fclose($handle);
        }
        foreach ($arr as $k => $value) {
            if ($value == null || !isset($value->content->word->content->usphone)) {
                continue;
            }
//                return count($value->content->word->content->trans);
            if (count($value->content->word->content->trans) === 1) {
                DB::table('kaoyan_words')->insert([
                    'wordHead' => $value->content->word->wordHead,
                    'usphone' => $value->content->word->content->usphone,
                    'ukphone' => $value->content->word->content->ukphone,
                    'tranCn' => $value->content->word->content->trans[0]->tranCn,
                    'pos' => $value->content->word->content->trans[0]->pos
                ]);
            } elseif (count($value->content->word->content->trans) === 2) {
                DB::table('kaoyan_words')->insert([
                    'wordHead' => $value->content->word->wordHead,
                    'usphone' => $value->content->word->content->usphone,
                    'ukphone' => $value->content->word->content->ukphone,
                    'tranCn' => $value->content->word->content->trans[0]->tranCn,
                    'tranCn1' => $value->content->word->content->trans[1]->tranCn,
                    'pos' => $value->content->word->content->trans[0]->pos,
                    'pos1' => $value->content->word->content->trans[1]->pos,
                ]);
            } elseif (count($value->content->word->content->trans) === 3) {
                DB::table('kaoyan_words')->insert([
                    'wordHead' => $value->content->word->wordHead,
                    'usphone' => $value->content->word->content->usphone,
                    'ukphone' => $value->content->word->content->ukphone,
                    'tranCn' => $value->content->word->content->trans[0]->tranCn,
                    'tranCn1' => $value->content->word->content->trans[1]->tranCn,
                    'tranCn2' => $value->content->word->content->trans[2]->tranCn,
                    'pos' => $value->content->word->content->trans[0]->pos,
                    'pos1' => $value->content->word->content->trans[1]->pos,
                    'pos2' => $value->content->word->content->trans[2]->pos,
                ]);
            }
        }


//            $contents = Storage::disk('local')->get('wordBook/cet4_3.json');
//            $contentsL = fgets($contents);
        return 'success';


    }

    /**
     * 书库名获取
     */
    public function book(Request $request)
    {
        $book = DB::table('words_json')->orderBy('person', 'desc')->get();
        return $this->jsonResponse('200', 'success', $book);
    }

    /**
     * 单词库json
     * @param Request $request num : 单词数量  wordName : 词库  page : 页数
     * @return JsonResponse
     */
    public
    function words(Request $request)
    {
        //验证传来参数
        $validator = Validator::make($request->all(), [
            'num' => 'required|numeric|size:3',
            'page' => 'required|integer|between:1,999',
            'wordName' => 'required'
        ], [
            'num.required' => 'num必须'
        ]);
        if ($validator->fails()) {
            //获取错误信息
            //$validator->errors();
            //自定义返回信息
            return $this->jsonResponse('403', '不合法的参数', []);
        }
        //接受验证通过参数
        try {
            $data = $validator->validated();
        } catch (ValidationException $e) {
            return $this->jsonResponse('501', '获取传参值失败', null);
        }
        $data = $validator->safe()->only(['num', 'page', 'wordName']);
        $num = $data['num'];
        $page = $data['page'];
        $wordName = $data['wordName'];
        //读取文件
        //获取文件名
        $filename = '../storage/app/wordBook/' . $wordName . '.json';
        //打开文操作件指针
        $handle = @fopen($filename, "r",);
        if ($handle) {
            //$i 读取总数  $j 偏移量
            $i = 0;
            $j = $num * ($page - 1);
            $arr = [];
            while (($buffer = fgets($handle, 4096)) !== false && $i < $num * $page) {
                if ($i >= $j) {
                    $arr [] = json_decode(($buffer));
                }
                $i++;
            }
            if (feof($handle)) {
                //echo "Error: unexpected fgets() fail\n";
                return $this->jsonResponse('406', '读取文件错误', null);
            }
            fclose($handle);
        } else {
            return $this->jsonResponse('400', '参数无效', null);
        }
        //返回响应数据
        return $this->jsonResponse('200', 'success', $arr);
    }

    public
    function jwt()
    {
//        auth()->payload()
    }
}
