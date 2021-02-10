@extends('adminlte::master')

@section('adminlte_css')
    @stack('css')
    @yield('css')
@stop

@section('classes_body'){{ ($auth_type ?? 'login') . '-page' }}@stop

@section('body')
    <div class="wrapper p-3" style="width: 100%">
        <div class="row">
            <div class="col-md-2 col-xs-0"></div>
            <div class="col-md-8 col-xs-12">
                {{-- Logo --}}
                <div class="{{ $auth_type ?? 'login' }}-logo">
                    {{-- <a href="{{ $dashboard_url }}"> --}}
                        <img src="{{ asset(config('adminlte.logo_img')) }}" height="50">
                        {!! config('adminlte.logo', '<b>Admin</b>LTE') !!}
                    {{-- </a> --}}
                </div>

                {{-- Card Box --}}
                <div class="card {{ config('adminlte.classes_auth_card', 'card-outline card-primary') }}">

                    {{-- Card Header --}}
                    @hasSection('auth_header')
                        <div class="card-header {{ config('adminlte.classes_auth_header', '') }}">
                            <h3 class="card-title float-none text-center">
                                @yield('auth_header')
                            </h3>
                        </div>
                    @endif

                    {{-- Card Body --}}
                    <div class="card-body {{ $auth_type ?? 'login' }}-card-body {{ config('adminlte.classes_auth_body', '') }}">
                        @yield('auth_body')
                    </div>

                    {{-- Card Footer --}}
                    @hasSection('auth_footer')
                        <div class="card-footer {{ config('adminlte.classes_auth_footer', '') }}">
                            @yield('auth_footer')
                        </div>
                    @endif

                    </div>
            </div>
        </div>
    </div>
@stop

@section('adminlte_js')
    @stack('js')
    @yield('js')
@stop
