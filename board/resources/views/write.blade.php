<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Write</title>
</head>
<body>
    <form action="{{route('boards.store')}}" method="post">
        @csrf {{-- csrf 공격에 대비해줘야 함 위치는 어디든 상관 없고 폼 안에만 있으면 됨--}}
        <label for="title">제목 : </label>
        <input type="text" name="title" id="title">
        <br>
        <label for="content">내용 : </label>   
        <textarea name="content" id="content"></textarea>
        <br>
        <button type="submit">작성</button>
    </form>
</body>
</html>