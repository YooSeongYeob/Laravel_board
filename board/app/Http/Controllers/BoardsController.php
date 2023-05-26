<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Boards;

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
        return view('write');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        $boards = new Boards([
            'title' => $req->input('title')
            ,'content' => $req->input('content')
        ]);
        $boards->save();
        // 이러면 인서트가 끝남 작성완료 되면 리스트페이지로 넘어가도록 하기
        return redirect('/boards'); // boards.index가 라우트 이름
        // 옐로퀀트가 업데이트랑 작성 다 알아서 해줌
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $boards = Boards::find($id);

        // 해당하는 레코드 아이디 정보를 계속 불러와줌 엘로퀀트의 핵심임
        // 엘로퀀트 모델로 값 불러오기 $boards = Boards::......
        // new는 인서트할 때
        
        $boards->hits++;
        $boards->save();

        // 기존 값을 가져오고 hits로 업데이트 내용을 작성하고 save를 하면 해당 내용이 업데이트가 된다

        // 단순히 화면 보여주는 거라서 바로 리턴해주는 것임
        return view('detail')->with('data', Boards::findOrfail($id));
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
        //
    }
        
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
