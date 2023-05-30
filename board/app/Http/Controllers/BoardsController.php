<?php
//------------------------------------------------------------------
// 최상단에 이력을 남긴다 프로젝트의 리더의 의견에 따라 달라짐
// 선생님이 현업에 있을 때 이 양식으로 썼다고 함 
// 프로젝트명 : laravel_board
// 디렉터리   : controllers
// 파일명     : BoardController.php
// 이력       : v001 0526 SY.Yoo new 
//              v002 0530 SY.Yoo 유효성 체크 추가 
//------------------------------------------------------------------
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\validator;

use Illuminate\Support\Facades\DB; // 파사드에 있는 db 객체임 모델 객체가 아님 orm이 아님 

use App\Models\Boards;


// 라라벨이 완전히 다 대체해주는 프레임워크가 아니기 때문에 
// PHP 정규식을 작성해줘야 할 때가 있음


class BoardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $result = Boards::select(['id','title','hits','created_at', 'updated_at'])->orderBy('hits', 'desc')->get();
        return view('list')->with('data', $result);
    } // 이게 orm 옐로퀀트 방식 편함     
    
    // 이 리스트는 컨트롤러랑 연관 있음
    // 처음에 이름부터 정의해줘야 함 with 쪽
   
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // v002 update start
        // return view('index') 기존의 소스코드를 파악하기 위해 지우지 않고 남겨둬야 함 언제 수정됐는지 번호로 수정함
        return view('write'); // v002 add (추가면 이렇게) new면 그냥 v002 대부분의 현장에서 이런 식으로 적용 중 기존 소스코드는 무조건 남겨둬야 함
        // v002 update end 2차 3차 프로젝트 때 필수기재 
        // 소스코드 만들고 리뷰를 하는 시점에서 버전 1이 끝이고 문제가 생겨 수정을 하면 버전2임 그렇게 계속 반복임
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req) // 새로운 게시글을 인서트 해주는 메소드임
    {
            // v002 add start
            // (이력 남기기)
        $req->validate([
            'title' => 'required|between:3,30' // 라라벨에서 제공해주는 유효성 체크 방법 사용
            ,'content' => 'required|max:1000'
        ]); // 유저한테 받은 값을 배열로 넣어줘야 함 타이틀이랑 컨텐트
            // min max와 between으로 설정 가능
            // v002 add end
            // validate로 배열 작성하면 작성 내용을 비우고 작성하기 버튼을 누르면 작성 완료가 되지 않음


        $boards = new Boards([
            'title' => $req->input('title')
            ,'content' => $req->input('content')
        ]);
        $boards->save();
        // 이러면 인서트가 끝남 작성완료 되면 리스트페이지로 넘어가도록 하기
        return redirect('/boards'); // boards.index가 라우트 이름
        // 옐로퀀트가 업데이트랑 작성 다 알아서 해줌
        // 인서트기 때문에 new를 작성해주는 것임
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $boards = Boards::find($id);  // 에러나면 false 처리 

        // 해당하는 레코드 아이디 정보를 계속 불러와줌 엘로퀀트의 핵심임
        // 엘로퀀트 모델로 값 불러오기 $boards = Boards::......
        // new는 인서트할 때
        
        $boards->hits++;
        $boards->save();

        // 기존 값을 가져오고 hits로 업데이트 내용을 작성하고 save를 하면 해당 내용이 업데이트가 된다

        // 단순히 화면 보여주는 거라서 바로 리턴해주는 것임
        return view('detail')->with('data', Boards::findOrfail($id)); // 예외처리를 해버림 404 error
    }
    // with를 이용해 바로 쏴준다 변수에 담아서 하기에는 비효율

    // 계속 증가시키려면 업데이트 해준다
    // 컨트롤러 먼저 만들고 뷰 만들고 필요한 뷰의 정보 다 가져와서 설정해주기

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $boards = Boards::find($id); // 셀렉트로 이미 데이터 가져왔기 때문에 필요 없음
        return view('edit')->with('data', $boards);
    // save는 인서트할 때 인서트가 실패하면 업데이트를 한다고 함 같이 되어있다고 함      
    }
    // show랑 edit랑 한 클래스 안이지만 각각 달라서 'data'를 중복으로 사용해도 됨


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function update(Request $request, $id)
    {

        // id는 url에 있어서 리퀘스트만 체크하면 출력이 안 됨 아이디가
        // 리퀘스트 안에 아이디를 넣어야 함
        // 아이디 배열 생성하고
        // 리퀘스트 머지 생성하고 어레이를 머지시킴
        // 그리고 리퀘스트를 실행해보면 아이디가 포함되어 있는 걸 볼 수가 있음
        
        // *** v002 add start ***
        // ID를 리퀘스트 객체에 머지시킴 ('합치다' 라는 의미)
        $arr = ['id' => $id];
        $request->merge($arr); // = $request->request->add(arr); 속도는 후자로 하는 게 더 빠를 수도 있음
        // *** v002 add end ***
        // return var_dump($request);

        // 먼저 자바스크립트에서 요청하고 서버에서도 확인해준다


        // 유효성 검사 방법 1
        /*
        $request->validate([
            'id'       => 'required|interger' // v002 add
            ,'title'   => 'required|between:3,30'
            ,'content' => 'required|max:1000' 
        ]);
        */

        // 유효성 검사 방법 2   
        
        /*
        $validator = validator::make(
            $request->only('id', 'title', 'content')
            ,[
            'id'       => 'required|interger' 
            ,'title'   => 'required|between:3,30'
            ,'content' => 'required|max:1000' 
            ]
        );
     
        if($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }


        // 컨트롤 스페이스랑 컨트롤 이용하기
        // 세션에 있는 것을 가져오는 것이 old 메소드의 역할
        */
        
        // 넘릭은 완전한 정수만
        // 인테저는 데이터 타입 안 맞아도 괜찮다고 함
        

        $rules = [
            'title' => 'required|between:3,255',
            'content' => 'required',
        ];
        
        // 유효성 검사 수행
        $messages = [
            'title.required' => '제목은 필수 항목입니다.',
            'title.between' => '제목은 3자에서 255자 사이여야 합니다.',
            'content.required' => '내용은 필수 항목입니다.',
        ];
        $request->validate($rules, $messages);
    
        // 검증이 성공한 경우에만 코드 실행
        $result = Boards::find($id);
        $result->title = $request['title'];
        $result->content = $request['content'];
        $result->save();
        
        return redirect()->route('boards.show', ['board' => $id]);
        }
             
        
    //    $arr = ['id' => $id];
    //    $req = new Request($arr);

    // 악성유저들 때문에 유효성 검사 꼭 해야 함 값이 이상하게 아무렇게나 들어갈 수가 있음
       
    // DB::table('Boards')->where('id','=',$id)->update([
    //       'title' => $request->title  
    //      ,'content' => $request->content 
    // ]);

    //    $result = Boards::find($id);
    //    $result->title = $request->title;
    //    $result->content = $request->content;
    //    $result->save();  

    //  return redirect('/boards/'.$id);
    //  return redirect()->route('boards.update', ['board' => $id]);
    
       // 쇼에서 수정 버튼 누르면 edit 수정페이지 -> edit는 데이터베이스에서 검색하고 edit 화면에다가 뷰로 바로 보여줌 내가 갖고 있는 페이지기 때문에
       // 수정 완료 버튼은 업데이트를 누름 수정 처리를 하고 자기 자신의 페이지가 없음 그래서 detail로 가는데 업데이트와 주소가 다르기 때문에 리다이렉트를 한 것임
       // 요청이 온 url은 업데이트 최종적으로는 show의 뷰를 보여줘야 함 그래서 redirect 해줘야 함 
       // list랑 index는 똑같은 애들이니 그냥 show를 설정함 리다이렉트 안 하고 


       // show는 페이지를 그냥 보여주는 것 
       // update는 수정 기능 자기페이지 가지고 있지 않음 그래서 리다이렉트 다른 페이지를 보여주는 것이기 때문임

    // ->with('data', Boards::findOrFail($id));
    // ->with('data', Boards::findOrFail($id));

    // 세그먼트 파라미터 보드라는 값이 있음 쇼에 보드의 아이디를 세팅 
    // api는 무조건 메소드로 구분 get이면 단순 검색 post면 새로운 데이터 입력 put 기존 데이터에다가 입력 delete는 삭제
    // URL이 바뀌면 무조건 리다이렉트 해줘야 함
    // return view('detail')->with('data', Boards::findOrFail($id));
    // 리다이렉트가 필요한 시점에서는 리다이렉트를 해줘야 함 

    // 업데이트 작업이 완료되고 데이터베이스에서 다시 가져와야 함
    // 데이터베이스에서 문제 없이 처리 완료되고 커밋 됐을 때
    //   $boards = Boards::find($id);
   
    // 내가 가지고 있는 처리 페이지가 있으면 그냥 뷰로 show로 표현

        // :: 는 쿼리빌더 모댈겍체를 써야지 ORM 
        // DB::table('Boards')->where('id','=',$id)->update([
        //     'title' => $request->title
        //     ,'content' => $request->content
        // ]); // 단순히 쿼리빌드로 데이터베이스에 질의함
    
    // $boards = Boards::find($id); // 객체를 이용한 ORM 

    // return view('detail')->with('data',$boards);
   
        

    // orm과 쿼리 빌드 차이 객체를 사용했냐 안 했냐 차이
    // boards


        // $boards = Request::find(1);

        // $boards->name = "수정됨";

        // $boards->save();

        // return \redirect('/boards');
       
   
    // 데이터베이스를 갱신하면 그 데이터 값을 다시 받아와야 한다 update할 때 주의
    // request를 이용해서 값을 받아들이고 다시 셀렉해서 가져오고 그걸 리스폰스
    // 수정페이지에서 값을 받아오고 값을 수정을 해서 상세페이지로
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $board = Boards::find($id)->delete(); // orm을 쓰면 소프트딜리트 기능을 사용 가능
        return redirect('/boards');
        
        // 앞에 메소드(get, post, put, delete 등등)로 구분한다
        // destroy update show url이 셋 다 같음 boards/3
        // url이 달랐으면 에러가 났을 것임

        // 넘어오는 값을 판별해서 에러처리 해줘야 함
        // 엘로퀀트 모델을 사용 중임
        // 잘못하면 다 삭제 됨
        // id 바로 삭제하면 되면 destory로 바로 실행해도 무방함
        // 딜리트는 객체를 먼저 만들고 체이닝 함
        // transaction 꼭 해줘야 함 rollback 처리
        // $board->delete();

        // DB::update()를 해줘야 함 얘는 소프트 딜리트가 안 됨
        // 엘로퀀트를 안 쓰면 소프트 딜리트가 안 됨
        // 바로 레코드가 지워지게 됨

        // Boards::destroy('id', $id)->delete();
        // return rdirect('/boards');
    }
}

// url  | index | show   |edit |update
// view | list  | detail |edit | X
// response 요청 받은 url과 되돌려줘야 하는 url이 다를 때는 리다이렉트 해줘야 함
