<?php

namespace App\Http\Controllers\LanguageList;

use App\Http\Controllers\Controller;
use App\Models\Language_list;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
