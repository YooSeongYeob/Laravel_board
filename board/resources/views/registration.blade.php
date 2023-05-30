@extends('layout.layout')

@section('title', 'Registration')

@section('contents')
<h1>REGISTRATION</h1>
@include('layout.errorsvalidate')
    <form action="{{route('users.registration.post')}}" method="post">
        @csrf
        <label for="name">name : </label>
        <input type="text" name="name" id="name">
        <label for="email">Email : </label>
        <input type="text" name="email" id="email">
        <label for="password">password : </label>
        <input type="password" name="password" id="password">
        <br>
        <label for="passwordchk">password Check : </label>
        <input type="password" name="passwordchk" id="passwordchk">
        <br><br>
        <button type="submit">Registration</button>
        <button type="button" onclick="location.href = '{{route('users.login')}}'">Cancel</button>
    </form> 
@endsection 