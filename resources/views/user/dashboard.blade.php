
@extends('layouts.app')

@section('title', 'Dashboard User')

@section('content')
    <h1>Selamat Datang, {{ auth()->user()->name }}</h1>
@endsection

