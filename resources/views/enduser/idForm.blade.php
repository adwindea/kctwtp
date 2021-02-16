@extends('adminlte::auth.auth-page')

@section('adminlte_css_pre')
@stop

@section('auth_header')
    <p class="text-center">Selamat datang di Sistem Upgrade Meter PLN</p>
    <small>Silahkan isi ID Pelanggan Anda untuk memulai.</small>
@stop

@section('auth_body')
    <form action="{{ route('detailPel') }}" method="post">
        {{ csrf_field() }}

        {{-- Email field --}}
        {{-- <div class="input-group mb-3">
            <input type="text" name="idpel" class="form-control" value="{{ old('idpel') }}" placeholder="ID Pelanggan" autofocus>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user"></span>
                </div>
            </div>
            @if($errors->has('idpel'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('idpel') }}</strong>
                </div>
            @endif
        </div> --}}
        <div class="input-group mb-3">
            <input type="text" name="no_meter" class="form-control" value="{{ old('no_meter') }}" placeholder="Nomor Meter" autofocus>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
            @if($errors->has('no_meter'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('no_meter') }}</strong>
                </div>
            @endif
        </div>


        {{-- Login field --}}
        <div class="row">
            <div class="col-7">

            </div>
            <div class="col-5">
                <button type=submit class="btn btn-block {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }}">
                    <span class="fas fa-search"></span>
                    Cari
                </button>
            </div>
        </div>

    </form>
@stop

@section('auth_footer')
    {{-- Password reset link --}}
    {{-- @if($password_reset_url ?? '')
        <p class="my-0">
            <a href="{{ $password_reset_url ?? '' }}">
                {{ __('adminlte::adminlte.i_forgot_my_password') }}
            </a>
        </p>
    @endif --}}

    {{-- Register link --}}
    {{-- @if($register_url ?? '')
        <p class="my-0">
            <a href="{{ $register_url ?? '' }}">
                {{ __('adminlte::adminlte.register_a_new_membership') }}
            </a>
        </p>
    @endif --}}
@stop

@section('js')
@if(session('error'))
<script type="text/javascript">
    $(document).Toasts('create', {
        title: 'Error',
        body: '{{session("error")}}',
        icon: 'fas fa-exclamation-triangle',
        class: 'bg-danger'
    });
</script>
@endif
@stop
