<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\NewYearController;
use App\Http\Controllers\Response\JsonController;
use App\Http\Controllers\Users\UserInfoController;
use App\Models\WxUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class WxAuthorizationsController extends Controller
{
    use JsonController;

    /**
     * https://api.htwyy.xyz/api/auth
     * 微信登录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function WxLogin(Request $request)
    {
        $msg = $request->all();
        if (!isset($msg['code']) || !isset($msg['userInfo'])) return $this->jsonResponse('403', '无效的参数', []);
        // 根据 code 获取微信 openid 和 session_key 第三方包 easyWechat
        $miniProgram = \EasyWeChat::miniProgram();
        $data = $miniProgram->auth->session($msg['code']);
        if (isset($data['errcode'])) return $this->jsonResponse('405', '获取用户唯一标识失败!', []);
        $userInfo = $msg['userInfo'];
        $userInfo['openid'] = $data['openid'];
        //return json_encode($userInfo);
        //获取ip
        $ipGetClass = new NewYearController();
        $ip = $ipGetClass->getIp();
        //ip查询位置
        $location = $ipGetClass->getLocation($ip);
        //查找用户是否存在
        $user = WxUser::where('openid', $data['openid'])->first();
        if ($user) {
            //更新用户
            $user->weixin_session_key = $data['session_key'];
            $user->nickname = $userInfo['nickName'];
            $user->avatar = $userInfo['avatarUrl'];
            $user->country = $userInfo['country'];
            $user->province = $userInfo['province'];
            $user->city = $userInfo['city'];
            $user->gender = $userInfo['gender'];
            $user->name = null;
            $user->email = null;
            $user->ip = $ip;
            $user->location = $location;
            //保存mysql
            $result = $user->save();
        } else {
            //新用户
            //存入用户数据
            //生成 limid
            $count = DB::table('wx_users')->count();
            if ($count == 0) {
                $limid = 666;
            } else {
                $limid = 13700 + $count;
            }
            $user = new WxUser;
            $user->openid = $data['openid'];
            $user->limid = $limid;
            $user->weixin_session_key = $data['session_key'];
            $user->nickname = $userInfo['nickName'];
            $user->avatar = $userInfo['avatarUrl'];
            $user->country = $userInfo['country'];
            $user->province = $userInfo['province'];
            $user->city = $userInfo['city'];
            $user->gender = $userInfo['gender'];
            $user->name = null;
            $user->email = null;
            $user->ip = $ip;
            $user->location = $location;
            //保存mysql
            $result = $user->save();
        }
        //生成jwt token
        $token = auth()->login($user);
        //用户信息
        $userInfoClass = new UserInfoController();
        $userInfo = $userInfoClass->index();
        return $this->jsonResponse('200', '成功', [
            'token' => $token,
            'userInfo' => $userInfo
        ]);
    }


    //登出
    public function WxLogout()
    {
        auth()->logout();
    }

    //已登录用户
    public function jwt()
    {

        return $this->jsonResponse('200', '身份未过期', []);
    }
}
