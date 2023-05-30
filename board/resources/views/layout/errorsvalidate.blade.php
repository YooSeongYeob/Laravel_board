 @if(count($errors) > 0) {{-- 얘가 있으면 실행되는 거임 --}}
        @foreach($errors->all() as $error)
            <div>{{$error}}</div>
        @endforeach
@endif