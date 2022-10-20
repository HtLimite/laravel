<?php

namespace App\Http\Controllers\LanguageList;

use App\Http\Controllers\Controller;
use App\Models\Language_list;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LanguageListController extends Controller
{
//        | GET|HEAD  | api/languageList     获取数据            | languageList.index   | App\Http\Controllers\languageList\languageListResCtroller@index            | api                                      |
    public function index(Request $request)
    {
        $obj = new Language_list();
        $data = $obj::all();
        $dataJson = json_decode($data);
        return response([
            'code' => 0,
            'msg' => '获取数据成功',
            'data' => $dataJson
        ]);
    }

    /**
     * 词库json添加 MySql
     * @return string
     */
    public function add()
    {
        return '禁止访问';
        $filename = '../storage/app/wordBook/CET6_3.json';
        $handle = @fopen($filename, "r",);
        if ($handle) {
            while (($buffer = fgets($handle, 99999)) !== false) {
//                $arr [] = json_decode(($buffer));
                //$buffer type: string
                $arr [] = "'".addslashes($buffer)."'";

            }
            if (!feof($handle)) {
                echo "Error: unexpected fgets() fail\n";
            }
            fclose($handle);
        }

        //存入数据库
        foreach ($arr as $value){
            $result = DB::insert("insert into CET6_3_jsonObj (json) values ($value)");
        }
        return 'success';
        //读取单词库json数据
//        $result = DB::table('KaoYan_3_jsonObj')->get();
//        foreach ($result as $value){
//            $json[] = json_decode($value->json);
//
//        }
//        return $json[99]->wordRank;
    }

    /**
     * 单词库添加 api
     * @return string
     */
    public function get()
    {
        return '禁止访问';
        $filename = '../storage/app/wordBook/KaoYan_3.json';
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


//            $contents = Storage::disk('local')->get('wordBook/CET4_3.json');
//            $contentsL = fgets($contents);
        return 'success';


    }

    /**
     * 单词库json api
     * @param Request $request num : 单词数量  wordName : 词库
     * @return array
     */
    public function words(Request $request)
    {
        $num = $request->input('num');
        $wordName = $request->input('wordName');
        $filename = '../storage/app/wordBook/' . $wordName . '.json';
        $handle = @fopen($filename, "r",);
        $i = 0;
        if ($handle) {
            while (($buffer = fgets($handle, 4096)) !== false && $i < $num) {
                $arr [] = json_decode(($buffer));
                $i++;

            }
            if (!feof($handle)) {
//                    echo "Error: unexpected fgets() fail\n";
            }
            fclose($handle);
        }
        return $arr;
    }


//        | POST      | api/languageList     添加           | languageList.store   | App\Http\Controllers\languageList\languageListResCtroller@store            | api                                      |
    public function store(Request $request)
    {

        $arr = $request->all();
        $languageL = new Language_list();
        $languageL->name = $arr['name'];
        $languageL->details = $arr['details'];
        $languageL->language_id = $arr['language_id'];
        $languageL->save();

        return $languageL;


    }


//        | GET|HEAD  | api/languageList/create          | languageList.create  | App\Http\Controllers\languageList\languageListResCtroller@create           | api                                      |

//        | GET|HEAD  | api/languageList/{languageList}      | languageList.show    | App\Http\Controllers\languageList\languageListResCtroller@show             | api                                      |

//        | PUT|PATCH | api/languageList/{languageList}      | languageList.update  | App\Http\Controllers\languageList\languageListResCtroller@update           | api                                      |


//        | DELETE    | api/languageList/{languageList}      | languageList.destroy | App\Http\Controllers\languageList\languageListResCtroller@destroy          | api                                      |

//        | GET|HEAD  | api/languageList/{languageList}/edit | languageList.edit    | App\Http\Controllers\languageList\languageListResCtroller@edit             | api

}
