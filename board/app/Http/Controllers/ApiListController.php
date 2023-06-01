<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Boards;

class ApiListController extends Controller
{
    function getlist($id) {
        $board = Boards::find($id);
        return response()->json([$board], 200); // http번호가 200번 자동으로 가게 해주는 것
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

    // // 메소드가 풋이면 수정 
    // function putlist () {
        
    // }

    // // 메소드가 딜리트면 삭제
    // function deletelist() {

    // }

// Api는 token이 필수다 / 혹은 jwt 쓰거나 라이브러리 사용

// url은 어떻게 해야할까 고민하고 유효성 검사를 한다

// postman에 아래를 적고 send 클릭하면 작동
// localhost/api/list/20
// localhost/api/list?title=test111&content=testtest222



//-----------------------------------------------------
// put, delete
//-----------------------------------------------------

// 겟 제외하고 쿼리스트링 x 
function putlist(Request $req, $id) {   // $req는 바디에 담긴 정보가 있음 $id는 세그먼트 파라미터가 담겨있다 바디 안에 인풋이라는 데이터라는 소리임 바디에 있는 정보라는 것이

    // 최상단에다가 보내줄 데이터 작성하기 
    $arrData = [
        'code' => '0'
        ,'msg' => ''
        // ,'errmsg' => []
    ]; // 이러한 형태로 제이슨에 보낼 것이다라는 arrData 변수 담기

    // 유효성 검사
   $data = $req->only('title', 'content'); // 배열로 담고 유효성을 체크할 때 첫 번째 아규먼트가 $data에 들어가야 하는데 새로운 데이터를 만들었고 유효성 체크를 함
   $data['id'] = $id;
   
   $validator = Validator::make($data, [
        'id' => 'required|integer|exists:boards' // 아이디 검증 | 테이블명 boards
        ,'title'   => 'required|between:3, 30'    
        ,'content' => 'required|max:2000'        // 유효성 체크해서 에러가 나면 체크한 결과를 발리데이터 객체로 저장함
    ]);                         // 그래서 한 번 더 체크해줘야 한다 함 if로 유효성 체크가 이어짐 
    // 발리데이터 객체를 새로 생성해서 유효성 체크 라라벨 300p에 있음

    if ($validator->fails()) {
        // 유효성 검사 실패 시 에러 처리
        $arrData['code'] = 'E01'; // 발리데이터에서 에러즈로 출력하는 결과값을 보려면 구글에서 validator errors 검색
        $arrData['msg'] = 'Validate Error'; // 한글 기피하라 한글하고 영어하고 바이트 수가 차이나서 가벼운데이터로 치환해줘야 한다함 한글 3 영어 1 byte라고 함
        $arrData['errmsg'] = $validator->errors()->all();
        return $arrData;
    } else {
         // 업데이트 처리 
    // 수정할 게시물을 조회하 유효성을 검사한 후 업데이트를 진행
    $boards = Boards::find($id);
    $boards->title = $req->title;
    $boards->content = $req->content;
    $boards->save();
    $arrData['code'] = '0'; 
    $arrData['msg'] = 'Success';
    }

    return $arrData;

    // 트라이 캐치로 예외처리 해주긴 해야 함 
    // 실서비스에서 데이터베이스에서 에러 안 난다 함
    // 만약을 위해서 트라이 캐치로 처리하면 된다 함
}
//     if (!$boards) {
//         // 게시물을 찾을 수 없는 경우 에러 처리
//         $arr['errorcode'] = '1';
//         $arr['msg'] = '게시물을 찾을 수 없습니다.';
//         return $arr;
//     }

//     $boards->title = $req->title;
//     $boards->content = $req->content;
//     $boards->save();

//     $arr['errorcode'] = '0';
//     $arr['msg'] = 'success';
//     $arr['data'] = $boards->only('id','title');

//     return $arr;
// }

function deletelist($id) {
    // 유효성 검사
    $arrData = [
        'code' => '0'
        ,'msg' => ''
    ];

    $data['id'] = $id;
    $validator = Validator::make($data, [
        'id' => 'required|integer|exists:boards'  
    ]);                     
    
    if($validator->fails()) {
        $arrData['code'] = 'E01';
        $arrData['msg'] = 'Error';
        $arrData['errmsg'] = 'id not found';
    } else{
        $board= Boards::find($id);
        if($board){
            $board->delete();
            $arrData['code'] = '0';
            $arrData['msg'] = 'success';
        } else{
            $arrData['code'] = 'E02';
            $arrData['msg'] = 'Already Deleted';
        }
    }
   
    return $arrData;
    
 }
 
}

    // 삭제할 게시물을 조회하고 유효성을 검사한 후 삭제를 진행

    // if (!$boards) {
    //     // 게시물을 찾을 수 없는 경우 에러 처리
    //     $arr['code'] = '1';
    //     $arr['msg'] = '게시물을 찾을 수 없습니다.';

    // $boards->delete();

    // $arr['code'] = '0';
    // $arr['msg'] = 'success';
    // }

    // 위의 코드에서 Boards는 게시물 모델을 나타내는 것으로 가정하고 사용되었습니다. 유효성 검사는 필요한 경우에 적절하게 추가해주시기 바랍니다. 또한, 코드 내에서 에러 처리를 위한 상황에 따른 조치를 취하고 있으니 필요에 따라 수정하셔야 합니다.
    
    // 비지니스 로직은 현업에서 배우는 거라고 하심 선생님이
    // php가 가장 중요 sql도 php만큼 중요 이 2개가 가장 중요 기본적으로 할 수 있어야 함
    // + HTML CSS LARAVEL / PURE PHP로 MVC로 만들어도 됨 라라벨 안 써도 프레임워크 안 쓰는 기업도 많다고 함
    // 비지니스 로직은 그대로임 라라벨로 컴포트만 해주는 것이기 때문임
    
    // 못하겠어도 생각이 안 나도 내 생각대로 구현을 하고 3차 때 수정한다고 함 2차 프로젝트를 하는 이유

    // 유효성 체크 방법 어떻게 체크하는지 잘 봐야 함 
    // 비지니스 로직 어떤 상황에서 어떻게 할건가 
    // 얻어가야 함 그리고 api로 만들어도 된다고 함
    // 각자 다른 프로젝트끼리도 api 통신 가능함
    
    // 엑세스 토큰이랑 관리가 어렵다고 함 소셜 로그인 구현이...

    // tests라는 폴더에 파일 만들어서 테스트 한다고 함
    
    
    // QA테스트 여러개의 기능을 쭉 이어서 테스트
    // 단위 테스트는 범위