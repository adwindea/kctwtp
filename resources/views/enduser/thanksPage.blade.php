@extends('adminlte::auth.auth-page')

@section('adminlte_css_pre')
@stop

@section('auth_header')
    <b>Sukses</b>
@stop

@section('auth_body')
    <p class="text-center">Terimakasih telah upgrade KWH meter.</p>
@stop

@section('auth_footer')
<div class="text-center">
    <a href="{{ route('idForm') }}" class="btn btn-success"><span class="fa fa-home"></span>Home</a>
</div>
@stop
