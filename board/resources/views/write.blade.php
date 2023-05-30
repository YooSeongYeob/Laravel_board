<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Write</title>
</head>
<body>
  @include('layout.errorsvalidate')
            {{-- 관리데이트 체크 끝 mvc모델보다 간편함 라라벨이 확실히 --}}
            {{-- 연상 값이 필요없으면 $key값 세팅 안 해줌 --}}
                                 
            {{-- 에러메시지를 다 표현할려면 for문을 돌려줘야 함  --}}
            {{-- 메시지 변경하려면 커스터마이징 해야 함 --}}
    
    <form action="{{route('boards.store')}}" method="post">
        @csrf {{-- csrf 공격에 대비해줘야 함 위치는 어디든 상관 없고 폼 안에만 있으면 됨--}}
        <label for="title">제목 : </label>
        <input type="text" name="title" id="title" value="{{old('title')}}"> {{-- old 작성하고 기존의 값 남겨주면 알아서 출력해줌 --}}
        <br>
        <label for="content">내용 : </label>   
        <textarea name="content" id="content">{{old('content')}}</textarea>
        <br>
        <button type="submit">작성</button>
        {{-- 패스워드는 올드 메소드 사용 X --}} 
        {{-- BoardController랑 write.blade.php 유효성 체크 완료함 --}}
    </form>
</body>
</html>