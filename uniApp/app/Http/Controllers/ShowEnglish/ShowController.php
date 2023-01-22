<?php

namespace App\Http\Controllers\ShowEnglish;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\JsonController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShowController extends Controller
{
    use JsonController;

    /**
     * 每日一次
     * @return JsonResponse
     */
    public function getOne(){
        //读取所有书文件名
        $fileName = glob('../storage/app/wordBook/*.json');
        //随机得到一本书文件名
        $randFileName =  $fileName[array_rand($fileName,1)];
        //分割为数据库对应书名
        $bookName = substr($randFileName, 24,-5);
        //总词数
        $tot = DB::table($bookName)->count();
        //随机得到一词
        $word = DB::table($bookName)->where('id', random_int(1,$tot))->get();
        return $this->jsonResponse('200','success',$word[0]);
    }
}
