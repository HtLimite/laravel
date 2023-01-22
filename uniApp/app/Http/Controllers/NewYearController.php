<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Response\JsonController;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Console\Input\Input;

class NewYearController extends Controller
{
    use JsonController;
    //接受弹幕
    public function index(Request $request){
        $message = $request->get('message');
        $preg = '/^(.*)(select|insert|into |delete|from |count|drop|join|union|table|database|update|truncate|asc\(|mid\(|char\(|xp_cmdshell|exec |master|net localgroup administrators|\"|:|net user|\| or )(.*)$/i';
        if (preg_match($preg,$message) != 0) return $this->jsonResponse('403', 'sql注入', null);
        if (!isset($message)) return $this->jsonResponse('200', 'success', $this->show());
        //客户端信息
        $client = $_SERVER['HTTP_USER_AGENT'];
        $ip = $this->getIp();
        $sql = DB::table('message_new_year')->insert([
            'ip' => $ip,
            'message' => $message,
            'client' => $client,
            'time' =>  Date(	'Y-m-d h:i:s')
        ]);
        $data = $this->show();
        return $this->jsonResponse('200', 'success', $data);
    }
    //展示祝福
    private function show(){
        return DB::table('message_new_year')->orderBy('id', 'desc')->get();
    }

    /**
     * ip 地址获取
     * @return mixed|string
     */
    public function getIp(){
        //ip获取
        //代理
        if (isset($_SERVER["HTTP_CLIENT_IP"]) && strcasecmp($_SERVER["HTTP_CLIENT_IP"], "unknown")) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        } else {
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]) && strcasecmp($_SERVER["HTTP_X_FORWARDED_FOR"], "unknown")) {
                $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $ip = trim(current($ip));
            } else {
                if (isset($_SERVER["REMOTE_ADDR"]) && strcasecmp($_SERVER["REMOTE_ADDR"], "unknown")) {
                    $ip = $_SERVER["REMOTE_ADDR"];
                } else {
                    if (isset ($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'],
                            "unknown")
                    ) {
                        $ip = $_SERVER['REMOTE_ADDR'];
                    } else {
                        $ip = "unknown";
                    }
                }
            }
        }
        //ip过滤
        if(!filter_var($ip, FILTER_VALIDATE_IP) || !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 || !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)))
        {
            $ip = '不合法的ip';
        }
        return $ip;
    }

    /**
     * 第三方获取 地理位置
     * 1.百度 https://sp0.baidu.com/8aQDcjqpAAV3otqbppnN2DJv/api.php?co=&resource_id=6006&oe=utf8&query=[ip]
     * 2. https://ip.useragentinfo.com/json?ip=<ip>  api: https://ip.useragentinfo.com/api
     * @param $ip
     * @return JsonResponse|string
     */
    public function getLocation($ip){
//        $ip = $request->get('ip');
        try {
            $response = Http::get("https://ip.useragentinfo.com/json?ip=" . $ip);
        }catch (ConnectionException $e){
//            return $this->jsonResponse('500','GuzzleHttp 异常',null);
            //GuzzleHttp 异常
        }
        //1.百度
//        if (!$response->successful() || !isset($response['data'][0]['location'])){
//            //第三方 API 异常
//            $location = 'unknow';
//        }else{
//            $location = $response['data'][0]['location'];
//        }
        //2.
//        dd($response);
        $response =  json_decode($response);
        $location = $response->province.''.$response->city;
        return $location;
    }
}
