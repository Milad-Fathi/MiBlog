@extends('layouts.app')

@section('content')

@if ($errors->any())
<div>
    <ul>
        @foreach ($errors->all() as $error)
        <li><b>{{ $error }}</b></li>
        @endforeach
    </ul>
</div>
@endif


<script src="{{ asset('tinymce/js/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>


<form action="{{ route('blog.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="input">
        <label>title: <br></label>
        <input type="text" name="title" value="{{ old('title') }}" />
    </div>
    <div class="input">
        <label>tag1: <br></label>
        <input type="text" name="tag1" value="{{ old('tag1') }}" />
    </div>
    <div class="input">
        <label>tag2: <br></label>
        <input type="text" name="tag2" value="{{ old('tag2') }}" />
    </div>
    <div class="input">
        <label>tag3: <br></label>
        <input type="text" name="tag3" value="{{ old('tag3') }}" />
    </div>
    <div class="input">
        <label>Cover Image: <br></label>
        <input type="file" name="cover_image" accept="image/*" />
    </div>
    <div class="input">
        <label>text: <br></label>
        <textarea id="editor" name="text" rows="10"></textarea>
    </div>
    <input class="button" type="submit" name="submit" value="Register" />
</form>


<script>
    tinymce.init({
        selector: '#editor',
        height: 500,
        width: 1450,
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