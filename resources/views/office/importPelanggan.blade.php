@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Import Pelanggan</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <div class="card">
                <form action="{{ route('importPelanggan') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                    <div class="card-body">
                        <div class="form-group">
                            <select id="pic" class="form-control select2bs4" name="pic" style="width:100%;">
                                <option value="">Pilih PIC</option>
                            @if(!empty($users))
                                @foreach($users as $u)
                                <option value="{{ Crypt::encrypt($u->id) }}">{{ $u->name }}</option>
                                @endforeach
                            @endif
                            </select>
                            <p class="text-danger">{{ $errors->first('pic') }}</p>
                            </label>
                        </div>
                        <div class="form-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="pelanggan" name="pelanggan">
                                <label class="custom-file-label" for="pelanggan">Import Excel (.xls/.xlsx)</label>
                                <p class="text-danger">{{ $errors->first('pelanggan') }}</p>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{asset('uploads/excel_format.xlsx')}}">Download Format</a>
                            <button type="submit" class="btn btn-success float-right">Import</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('plugins.Select2', true)

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
<script type="text/javascript">
    $('.select2bs4').select2();
</script>
@stop
