@extends('adminlte::page')

@section('title', 'Data Pelanggan')

@section('content_header')
    <h1>Data Pelanggan</h1>
@stop

@section('content')
    <p>Halo {{Auth::user()->name}} tampan!</p>
@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    {{-- <script> console.log('Hi!'); </script> --}}
@stop
