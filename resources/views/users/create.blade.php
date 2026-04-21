@extends('layouts.app')

@section('title', 'Initiate User')

@section('content')
    @include('users.partials.form', ['user' => $user, 'mode' => 'create'])
@endsection
