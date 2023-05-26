<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Write</title>
</head>
<body>
    <form action="{{route('boards.update', ['board' => $data->id])}}" method="post">
     {{-- 문자열 안에 홑따옴표 '' 넣지마라 같이 문자로 인식해서 404 Error 남 --}}
        @csrf   {{-- csrf 공격에 대비해줘야 함 위치는 어디든 상관 없고 폼 안에만 있으면 됨--}}
        @method('put') 
        <label for="title">제목 : </label>
        <input type="text" name="title" id="title" value="{{$data->title}}">
        <br>
        <label for="content">내용 : </label>   
        <textarea name="content" id="content">{{$data->content}}</textarea>
        <br>
        <button type="submit">수정</button>
        <button type="button" onclick="location.href='{{route('boards.show', ['board' => $data->id])}}'">취소</button>
    </form>

    {{-- method 풋 또는 딜리트 따로 폼 태그 내부에서 설정해줘야 함 --}}
    {{-- 수정메소드는 풋임 boards.update라고 되어 있음 리스트에 --}}
    {{-- 인서트는 초기값 비어있지만 업데이트는 값이 있어야 한다 --}}
</body>
</html>