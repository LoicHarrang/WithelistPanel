@extends('layouts.dash')
@section('title', $page->title)
@section('content')
<div class="container">
    <br>
    {!! $page->content !!}
</div>
@endsection