@extends('_layouts.app')

@section('title'){{ $category->label }}@endsection

@section('description'){{ $category->meta_description }}@endsection

@section('content')
    <section>
        {{ $category->label }}
    </section>
@endsection
