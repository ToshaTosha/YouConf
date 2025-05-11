@extends('layouts.app')

@section('content')
<article class="static-page">
    <h1>{{ $page->title }}</h1>

    <div class="page-content">
        {!! $page->content !!}
    </div>
</article>
@endsection