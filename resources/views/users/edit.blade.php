@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
    @include('users.partials.form', ['user' => $user, 'mode' => 'edit'])
@endsection
