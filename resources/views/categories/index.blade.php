@extends('_layouts.app')

@section('title',__('All categories'))

@section('description',__('All categories'))

@section('content')
    <h1>{{ __('All categories') }}</h1>

    {!!  breadcrumb([['label' => __('Categories')]]) !!}
@endsection
