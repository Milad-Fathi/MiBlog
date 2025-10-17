
@extends('layouts.layout')


@section('content')
<div>
    <p>sign up</p>
    <form action="/home" method="POST">
        @csrf 

        <label>Name:</label>
        <input type="text" name="name" required>
        <br/>
        <label>Email:</label>
        <input type="email" name="email" required>
        <br/>
        <label>Password:</label>
        <input type="password" name="password" required>
        <br/>
        <input class="button" type="submit" value="Sign Up">
    </form>
</div>
@endsection

