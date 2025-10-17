@extends('layouts.app')

@section('content')

<div class="show_content">
    <div class="content">

        @if($blog->cover_image)
        <img src="{{ $blog->cover_image_url }}" alt="{{ $blog->title }}" class="cover_image_content">
        @endif
        
        @if($summary_text)
            <div class="summary"><b><i>AI generated summary:</i> <br> </b><i>{!!$summary_text!!}</i></div>
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
        <div class="text_main"> {!! $blog->text !!}</div>
        <br>
    </div>
</div>



<div class="all_comments">
    <p class="tag"><b>Comments</b></p>

    @foreach($comments as $comment)
    <div class="comment">
        <div class="author">{{ $comment->user->name }}</div>
        <div class="date"> ,{{ $comment->created_at }}</div>
        <div class="text">{!! $comment->text !!}</div>
    </div>
    <br><br><br><br>
    @endforeach
</div>


<script src="{{ asset('tinymce/js/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>


@auth
<div>
    @if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
            <li><b>{{ $error }}</b></li>
            @endforeach
        </ul>
    </div>
    @endif

    <form class="all_comments" action="{{ route('comment.store') }}" method="POST">
        @csrf
        <label for=""><b>Write comment :</b></label>
        <br>
        <textarea id="editor" name="text" rows="10"></textarea>

        <input type="hidden" name="blog_id" value="{{ $blog->id }}" />

        <input class="button" type="submit" />

    </form>
</div>
@else
<a id="log_comment" class="all_comments" href="{{ route('login') }}"><b>Login For Submit comment</b></a>
@endauth


<script>
    tinymce.init({
        selector: '#editor',
        height: 200,
        width: 600,
        menubar: false,
        plugins: [
            'advlist autolink link lists image charmap print preview anchor textcolor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table contextmenu paste code help'
        ],
        toolbar: 'insert | undo redo | styleselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help'
    });
</script>

@endsection