<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail</title>
</head>
<body>
    <div>
        글번호: {{$data->id}}
        <br>
        제목 :  {{$data->title}}
        <br>
        내용 : {{$data->content}}
        <br>
        등록일자 : {{$data->created_at}}
        <br>
        수정일자 : {{$data->updated_at}} {{--수정일자와 조회수 테이블을 분리하거나 모델에 수정일자가 수정 안 되게끔 해주던지--}}
        <br>
        조회수 : {{$data->hits}}
    </div>
    <button type="button" onclick="location.href='{{route('boards.index')}}'">리스트 페이지로</button>
    <button type="button" onclick="location.href='{{route('boards.edit', ['board' => $data->id])}}'">수정 페이지로</button>
    {{-- 겟으로 가기 때문에  php artisan route:list 배열은 콤마가 아니라 두 줄 화살표로 표시 --}}
</body>
</html>