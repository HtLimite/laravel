<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response\JsonController;
use App\Models\UserHelp;
use App\Models\UserRecord;
use App\Models\UserRecordHistory;
use App\Models\WxUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\ValidationException;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Ramsey\Uuid\Type\Integer;

class UserMessageController extends Controller
{
    use JsonController;

    //POST      | api/userMessage                      | userMessage.store
    /**
     * 更新 创建 单词计划
     * @param Request $request
     * @return JsonResponse|MessageBag
     */
    public function store(Request $request){
        //获取用户信息
        $user =  auth()->user();
        //验证传来参数
        $validator = Validator::make($request->all(),[
            'plan_book' => 'required|',
            'plan_day_words' => 'required|integer|between:1,999',
        ],[
            'plan_book.required' => 'plan_book必须',
            'plan_day_words.required' => 'plan_day_words必须',
        ]);
        if($validator->fails()){
            //获取错误信息
            return $validator->errors();
            //自定义返回信息
//            return $this->jsonResponse('403', '不合法的参数',[]);
        }
        //接受验证通过参数
        try {
            $data = $validator->validated();
            $data['openid'] =$user->openid;
        } catch (ValidationException $e) {
            return $this->jsonResponse('501','获取传参值失败',null);
        }
        $userRecord = UserRecord::where('openid' , $data['openid'])->first();
        $totalNum = DB::table($data['plan_book'])->count();
        if(!isset($userRecord) || $userRecord->plan_book != $data['plan_book']) {
            if(isset($userRecord) && $userRecord->plan_book != $data['plan_book']){
                //更换图书
                //保存历史纪录
                if($userRecord->finish_word >= $userRecord->plan_day_words){
                    //累计打卡大于 一天
                    $userRecordHistory = new UserRecordHistory();
                    $userRecordHistory->openid = $userRecord->openid;
                    $userRecordHistory->plan_book = $userRecord->plan_book;
                    $userRecordHistory->plan_days = $userRecord->plan_days;
                    $userRecordHistory->plan_day_words = $userRecord->plan_day_words;
                    $userRecordHistory->finish_word = $userRecord->finish_word;
                    $userRecordHistory->finish_list = $userRecord->finish_list;
                    $userRecordHistory->finish_day = $userRecord->finish_day;
                    $userRecordHistory->random_list = $userRecord->random_list;
                    $userRecordHistory->like_list = $userRecord->like_list;
                    $userRecordHistory->save();
                }

                //初始化新计划
            }
            //创建
            //初始化
            //$totalNum = DB::table($data['plan_book'])->count();
            $arr = [];
            for ($i = 0; $i < $totalNum; $i++){
                $arr[$i] = $i+1;
            }
            //随机打乱词库序列
            shuffle($arr);
            $data['random_list'] = json_encode($arr);
            $data['finish_list'] = json_encode([]);
            $data['like_list'] = json_encode([]);
            $data['finish_word'] = 0;
            $data['finish_day'] = 0;
            //存入数据库
            //用户单词信息记录表
            if(!isset($userRecord)){
                $userRecord = new UserRecord();
                $userRecord->openid = $data['openid'];
            }
            $userRecord->random_list = $data['random_list'];
            $userRecord->finish_list = $data['finish_list'];
            $userRecord->like_list = $data['like_list'];
            $userRecord->finish_word = $data['finish_word'];
            $userRecord->finish_day = $data['finish_day'];
            $userRecord->rank = 0;
        }
        //更新 创建
        $data['plan_days'] = ceil($totalNum / ($data['plan_day_words']-$userRecord->finish_word));
        $userRecord->plan_book = $data['plan_book'];
        $userRecord->plan_days = $data['plan_days'];
        $userRecord->plan_day_words = $data['plan_day_words'];

        //创建 或 更新
        $result = $userRecord->save();
        if($result){
//            //返回查询结果
//            $userRecord = UserRecord::where('openid', $data['openid'])->first();
//            unset($userRecord->id);
//            unset($userRecord->openid);
//            unset($userRecord->random_list);
            return $this->show($data['openid']);
        }
        return $this->jsonResponse('500', '计划创建/更新失败', null);
    }

    //GET|HEAD  | api/userMessage/{userMessage}        | userMessage.show
    /**
     * 获取用户单划单词信息
     * @param $openid
     * @return JsonResponse
     */
    public function show($openid){
        //openid 验证
        //$data = $this->openidValidate($openid);
        //if($data[0] != 200) return $this->jsonResponse($data[0], $data[1],$data[2]);
        $openid =  auth()->user()->openid;
        $userRecord = UserRecord::where('openid', $openid)->first();
        if(!isset($userRecord)){
            //初始化
            return $this->jsonResponse('205', '初始化', null);
        }
        //乱序列表
        $wordsList = json_decode($userRecord->random_list);
        //收藏列表
        $likeList = json_decode($userRecord->like_list);
        $planDayWords = $userRecord->plan_day_words;
        $finisList = json_decode($userRecord->finish_list);
        $wordsList = array_diff($wordsList, $finisList);
        $wordsList = array_slice($wordsList, 0 ,$planDayWords);
        //原有id顺序
        $ids_ordered = implode(',',$wordsList);
        $ids_ordered_like = implode(',',$likeList);
        //当天单词
        $word = DB::table($userRecord->plan_book)
            ->whereIn('id',$wordsList)
            ->orderByRaw(DB::raw("FIELD(id, $ids_ordered)"))
            ->get();
        //收藏单词
        $like = DB::table($userRecord->plan_book)
            ->whereIn('id',$likeList)
            ->orderByRaw(DB::raw("FIELD(id, $ids_ordered_like)"))
            ->get();
        //个人信息
        $userInfoClass = new UserInfoController();
        $userInfo = $userInfoClass->index();
        unset($userRecord->id);
        unset($userRecord->openid);
        unset($userRecord->random_list);
        return $this->jsonResponse('200', '获取用户计划成功', [
            'userInfo' => $userInfo,
            'userRecord' => $userRecord,
            'userWordsDay' => $word,
            'likeWords' => $like
        ]);
    }

    //GET|HEAD  | api/userMessage/{userMessage}/edit   | userMessage.edit
    public function edit($openid){
        var_dump($openid);
    }


    //PUT|PATCH | api/userMessage/{userMessage}        | userMessage.update
    /**
     * 更新已背单词
     * likeList 数组 likeList[1,2...]  已收藏单词 id
     * finishList 数组 finishList[1,2...]  已完成单词 id
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request){
        $validator = Validator::make($request->all(),[
            'finishList' => 'array',
            'likeList' => 'array',
            'finishList.*' => 'integer|min:1',
            'likeList.*' => 'integer|min:1',
            'del' => 'boolean',
//            'finishDay' => 'integer|between:1,1'
        ]);
        if($validator->fails()) return $this->jsonResponse('403', '参数不合法', $validator->errors());
        try {
            $data = $validator->validated();
            $data['openid'] = auth()->user()->openid;
        } catch (ValidationException $e) {
            return $this->jsonResponse('501','获取传参值失败',$e);
        }
        $userRecord = UserRecord::where('openid' , $data['openid'])->first();
        if(!isset($userRecord)) return $this->jsonResponse('403', '无效的用户', null);
        if(isset($data['del']) && $data['del']){
            //取消收藏
            $likeList = $data['likeList'];
            $nowLikeList = json_decode($userRecord->like_list);
            foreach ($nowLikeList as $index => $value1){
                foreach ($likeList as $value2){
                    if($value2 == $value1){
                        unset($nowLikeList[$index]);
                    }
                }
            }
            $nowLikeList = array_values($nowLikeList);
            $userRecord->like_list = json_encode($nowLikeList);
            $result = $userRecord->save();
            if($result){
                return $this->jsonResponse('200', '取消收藏成功', $userRecord);
            }
            return $this->jsonResponse('500', '取消收藏失败!', null);

        }
        isset($data['finishList']) ? : $data['finishList'] = [];
        isset($data['likeList']) ? : $data['likeList'] = [];
        $finishList = $data['finishList'];
        $likeList = $data['likeList'];
        $countFinishList = count($finishList);
        $userRecord->finish_word += $countFinishList;
        $nowList = json_decode($userRecord->finish_list);
        $nowLikeList = json_decode($userRecord->like_list);
        $diffFinnish = array_diff($finishList , $nowList);
        if(count($diffFinnish) != $countFinishList ) return $this->jsonResponse('403', '无效的已完成词数索引', $data);
        foreach ($finishList as $value){
            $index = count($nowList)-1;
            $nowList[++$index] = (int)$value;
        }
        $likeListInt = [];
        foreach ($likeList as $value){
            $likeListInt[] = (int)$value;
        }
        $afterLikeList = array_merge($nowLikeList, $likeListInt);
        //去重
        $afterLikeList = array_unique($afterLikeList);
        //键值对重建
        $afterLikeList = array_values($afterLikeList);
        $userRecord->like_list = json_encode($afterLikeList);;
        $userRecord->finish_list = json_encode($nowList);;

        $cumulateDay = floor(count($nowList)/$userRecord->plan_day_words);
        $userRecord->finish_day = $cumulateDay;
        //排行榜值
        $userRecord->rank = $userRecord->finish_word*3 + $userRecord->finish_day*7;
        $result = $userRecord->save();
        if($result){
            return $this->jsonResponse('200', '词库记录更新成功', $userRecord);
        }
        return $this->jsonResponse('500', '更新失败', null);

    }



    /**
     * post Route: /userMessage/rank
     * 获取单词打卡排行榜
     * 完成天数占比 70% 完成单词数目占比 30%
     * @return JsonResponse
     */
    public function rank(){
        //我的排名
        $rank = DB::table('user_record')->where('openid',auth()->user()->openid)->get('rank');
        $myRank = DB::table('user_record')->where('rank','>=', $rank[0]->rank)->orderBy('rank','desc')->count();
        //前十排名
        $userRank = DB::table('user_record')->orderBy('rank','desc')->limit(3)->join('wx_users','user_record.openid', '=', 'wx_users.openid')->get();
        foreach ($userRank as $index => $user){
            $user->rank = $index + 1;
            unset($user->id);
            unset($user->openid);
            unset($user->finish_list);
            unset($user->random_list);
            unset($user->weixin_session_key);
        }
        $userRank['myRank'] = $myRank;
        return $this->jsonResponse('200','获取排行榜成功',$userRank);
    }

    /**
     * 用户反馈 userMessage/help post
     * @param Request $request
     * @return JsonResponse|void
     */
    public function help(Request $request){
        $validator = Validator::make($request->all(),[
            'message' => 'required|between: 1, 1000|string'
        ]);
        if($validator->fails()) return $this->jsonResponse('403', '参数不合法', $validator->errors());
        try {
            $data = $validator->validated();
            $data['openid'] = auth()->user()->openid;
        } catch (ValidationException $e) {
            return $this->jsonResponse('501','获取传参值失败',$e);
        }
        //正则过滤
        $preg = '/^(.*)(select|insert|into |delete|from |count|drop|join|union|table|database|update|truncate|asc\(|mid\(|char\(|xp_cmdshell|exec |master|net localgroup administrators|\"|:|net user|\| or )(.*)$/i';
        if (preg_match($preg,$data['message']) != 0) return $this->jsonResponse('403', 'sql注入', null);
        $userHelp = new UserHelp();
        $userHelp->openid = $data['openid'];
        $userHelp->message = $data['message'];
        $result = $userHelp->save();
        if($result){
            return $this->jsonResponse('200', '反馈成功', null);
        }
        return $this->jsonResponse('500', '反馈失败', null);

    }

    /**
     * 用户收藏单词 userMessage/collect post
     * @param Request $request
     * @return JsonResponse
     */
    public function collect(Request $request){
        $del = $request->get('del');

        $offset = $request->get('offset');
        if($del){
            //取消收藏
            $userRecord = UserRecord::where('openid',auth()->user()->openid)->first();


        }
        $userRecord = UserRecord::where('openid',auth()->user()->openid)->first();
        $collect_list = json_decode($userRecord->like_list);
        $plan_book = $userRecord->plan_book;
        //原有顺序 implode 数组值变为字符串 以 , 分隔
        $ids_ordered = implode(',',$collect_list);
        $collect_words = DB::table($plan_book)
            ->whereIn('id', $collect_list)
            ->orderByRaw(DB::raw("FIELD(id, $ids_ordered)"))
            ->offset($offset)
            ->limit(10)
            ->get();
        if($collect_words){
            return $this->jsonResponse('200', 'success', $collect_words);
        }
        return $this->jsonResponse('500', 'fail', '服务器查询失败!');
    }

    //GET|HEAD  | api/userMessage                      | userMessage.index
//    public function index(Request $request){
//        $user = UserRecord::where('openid',$request->get('openid'))->first();
//        $message = DB::table('cet4_3')->where('id', 1)->first();
//        var_dump(json_decode($user->random_list));
////        $json = json_decode($message);
////        var_dump(($message->json));
//    }


    /**
     * openid 验证
     * @param $openid
     * @return array
     */
    protected function openidValidate($openid): array
    {
        $validator = Validator::make(array($openid),[
            'required',
            'regex:/^[a-zA-Z0-9-_]*$/',
        ]);
        if($validator->fails()) return ['403', '参数不合法', $validator->errors()];
        try {
            $data = $validator->validated();
        } catch (ValidationException $e) {
            return ['501','获取传参值失败',$e];
        }
        //jwt 中间件已经验证 非必要
//        $user = WxUser::where('openid',$openid)->count();
//        if ($user != 1) return ['401','该用户不存在',$openid];
        return ['200','验证通过', $data[0]];
    }
}
