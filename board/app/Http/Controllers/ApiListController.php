<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Boards;

class ApiListController extends Controller
{
    function getlist($id) {
        $board = Boards::find($id);
        return response()->json([$board], 200);
    }


    function postlist(Request $req) {
        // 유효성 체크 필요
        // 메소드를 이용하였는데 강제적으로 라라벨 에러 핸들링을 가져가는 순간 처리를 해버린다 함
        // new로 새로운 관리를 하는 방법이 있어서 에러가 나도 강제로 에러 핸들링한테 붙잡혀 가지 않는다 함
        // 주의 필요하다 함

        $boards = new Boards([
            'title' => $req->title
            ,'content' => $req->content
        ]);
        $boards->save();

        $arr['errorcode'] = '0';
        $arr['msg'] = 'success';
        $arr['data'] = $boards->only('id','title');
        
        return $arr;
        
        // return response()->json($boards, 200);
    }
}
// postman에 아래를 적고 send 클릭하면 작동
// localhost/api/list/20
// localhost/api/list?title=test111&content=testtest222


