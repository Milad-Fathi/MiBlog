@extends('layouts.app')

@section('content')

<div class="main">
    <div class="content">
        @foreach($blogs as $blog)

        @if($blog->cover_image)
        <img src="{{ $blog->cover_image_url }}" alt="{{ $blog->title }}" class="cover_image">
        @endif
        <div class="title_main"> {{ $blog->title }}</div>
        <div class="tag_main">
            @if($blog->tag1)
            #{{ $blog->tag1 }}
            @endif
        </div>
        <div class="tag_main">
            @if($blog->tag2)
            #{{ $blog->tag2 }}
            @endif
        </div>
        <div class="tag_main">
            @if($blog->tag3)
            #{{ $blog->tag3 }}
            @endif
        </div>
        <div class="date_main"> , {{ $blog->created_at }}</div>
        <div class="text_main_index"> {!! $blog->text !!} ...</div>
        <div class="readmore_main"><a href="{{ route( 'blog.show', $blog->id ) }}">Read More</a></div>
        <br><br><br><br>
        @endforeach
    </div>
</div>

@endsection