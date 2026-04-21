@extends('layouts.app')

@section('title', 'Editar Usuário')

@section('content')
    @include('users.partials.form', ['user' => $user, 'mode' => 'edit'])
@endsection
