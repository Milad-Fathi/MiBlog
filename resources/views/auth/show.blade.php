
@extends('layouts.layout')


@section('content')


<p>hi</p>


<ul>
    <li><b>name: </b> {{ $user->name }}</li>
    <li><b>email: </b>  {{ $user->email }}</li>
    
    
    <br><br><br><br><br>
</ul>

@endsection