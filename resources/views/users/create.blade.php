@extends('layouts.app')

@section('title', 'Cadastrar Usuário')

@section('content')
    @include('users.partials.form', ['user' => $user, 'mode' => 'create'])
@endsection
