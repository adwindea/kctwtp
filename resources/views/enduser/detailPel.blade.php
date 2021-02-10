@extends('adminlte::enduser')

@section('adminlte_css_pre')
@stop

@section('auth_header')
    <p class="text-center">Data Pelanggan</p>
@stop

@section('auth_body')
    {{-- <form action="{{ route('detailPel') }}" method="post">
        {{ csrf_field() }} --}}

        {{-- Email field --}}
    <div class="row">
        <div class="col-12">
            <table class="table table-stripped table-hover">
                <tr>
                    <th>Nama Pelanggan</th>
                    <th>:</th>
                    <td>{{ $nama }}</td>
                </tr>
                <tr>
                    <th>ID Pelanggan</th>
                    <th>:</th>
                    <td>{{ $idpel }}</td>
                </tr>
                <tr>
                    <th>Tarif</th>
                    <th>:</th>
                    <td>{{ $tarif }}</td>
                </tr>
                <tr>
                    <th>Daya</th>
                    <th>:</th>
                    <td>{{ number_format($daya, 0, '', '') }}</td>
                </tr>
                <tr>
                    <th>Alamat</th>
                    <th>:</th>
                    <td>{{ $alamat }}</td>
                </tr>
                <tr>
                    <th>Versi KWh</th>
                    <th>:</th>
                    <td>KRN{{ $vkrn }}</td>
                </tr>
                @if($upgraded == 1 && $confirmed == 0)
                <tr>
                    <th>Status</th>
                    <th>:</th>
                    <td><span class="badge badge-warning">Menunggu konfirmasi petugas</span></td>
                </tr>
                @elseif($upgraded == 1 && $confirmed == 1)
                <tr>
                    <th>Status</th>
                    <th>:</th>
                    <td><span class="badge badge-success">Terupdate</span></td>
                </tr>
                @endif
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            @if($upgraded == 0)
                @if($vkrn == 41 or $vkrn == 42)
                    <span>KWH meter Anda saat ini versi KRN{{$vkrn}}. Diperlukan update ke versi KRN43. Silahkan tekan tombol 'Update' untuk mendapatkan token untuk update software</span>
                @elseif($vkrn == 43)
                    <span>KWH meter Anda saat ini versi KRN43 dan tidak diperlukan update</span>
                @endif
            @endif
        </div>
    </div>

    {{-- </form> --}}
@stop

@section('auth_footer')
    <div class="row">
        <div class="col-12">
            @if($upgraded == 0)
                @if($vkrn == 41 or $vkrn == 42)
                <a href="{{ route('updateToken', Crypt::encryptString($id)) }}" type=submit class="btn btn-block btn-flat btn-primary text-center">
                    <span class="fas fa-sign-in-alt"></span>
                    Update
                </a>
                @endif
            @endif
        </div>
    </div>
@stop
