@extends('adminlte::page')

@section('title', 'Data Pelanggan')

@section('content_header')
    <h1>Data Pelanggan</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                {{-- <div class="card-header">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <form id="form-filter">
                                <div id="filterbox" class="collapse">
                                    <div class="row">
                                        <div class="col-md-3 col-sm-6 col-12">
                                            <label>From: </label>
                                            <input type="text" class="form-control datepicker" id="from" autocomplete="off">
                                        </div>
                                        <div class="col-md-3 col-sm-6 col-12">
                                            <label>To: </label>
                                            <input type="text" class="form-control datepicker" id="to" autocomplete="off">
                                        </div>
                                        <div class="col-md-6 col-sm-12 col-12">
                                            <label>Category: </label>
                                            <select class="form-control select2bs4" multiple="multiple" id="category" style="width: 100%;">
                                                @isset($categories)
                                                    @foreach($categories as $cat)
                                                <option value="{{ \Crypt::encrypt($cat->id) }}">{{ $cat->category_name }}</option>
                                                    @endforeach
                                                @endisset
                                            </select>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                            <button type="button" id="btn-filter" class="btn btn-primary btn-sm float-right"><i class="fa fa-filter"></i> Filter</button>
                                            <button type="button" id="btn-reset" class="btn btn-default btn-sm"><i class="fa fa-close"></i> Reset</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <a data-toggle="collapse" class="btn btn-primary pull-right btn-xs btn-flat collapsed" href="#filterbox" aria-expanded="false"><i class="fa fa-filter"></i> Filter box</a>
                        </div>
                    </div>
                </div> --}}
                <div class="card-body">
                    <table class="table" id="pelanggan_table" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Gambar</th>
                                <th class="text-center">ID Pelanggan</th>
                                <th class="text-center">No. Meter</th>
                                <th class="text-center" style="min-width:120px;">Nama</th>
                                <th class="text-center" style="min-width:250px;">Alamat</th>
                                <th class="text-center">Tarif</th>
                                <th class="text-center">Daya</th>
                                <th class="text-center">KRN</th>
                                <th class="text-center">VKRN</th>
                                <th class="text-center">KCT 1A</th>
                                <th class="text-center">KCT 1B</th>
                                <th class="text-center">KCT 2A</th>
                                <th class="text-center">KCT 2B</th>
                                <th class="text-center">KCT1</th>
                                <th class="text-center">KCT2</th>
                                <th class="text-center">Tanggal Input</th>
                                <th class="text-center">Tanggal Konfirmasi</th>
                                <th class="text-center">Konfirmasi Oleh</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalconfirm" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Konfirmasi Update</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <a id="link" target="_blank">
                                <img id="lampiran" style="width: 100%;">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <input type="hidden" id="xid">
                    <button type="button" class="btn btn-danger" onclick="confirmUpgrade(0)">Reject</button>
                    <button type="button" class="btn btn-success" onclick="confirmUpgrade(1)">Accept</button>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('plugins.Datatables', true)

@section('css')
<link href="/datatables/datatables-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<link href="/datatables/datatables-buttons/css/buttons.bootstrap4.min.css" rel="stylesheet">
@stop

@section('js')
<script src="/datatables/jquery.dataTables.min.js"></script>
<script src="/datatables/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="/datatables/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="/datatables/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="/datatables/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="/datatables/datatables-buttons/js/buttons.print.min.js"></script>
<script src="/datatables/datatables-buttons/js/buttons.colVis.min.js"></script>
<script src="/datatables/JSZip-2.5.0/jszip.min.js"></script>
<script src="/datatables/pdfmake-0.1.36/pdfmake.min.js"></script>
<script src="/datatables/pdfmake-0.1.36/vfs_fonts.js"></script>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var table = $('#pelanggan_table').DataTable({
        processing: true,
        serverSide: true,
        order: [],
        scrollX: true,
        stateSave: true,
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        ajax: {
            url: '{!! route('dataPelangganTable') !!}',
            type: "POST",
            data: function (data) {
                data.tes = 1
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'}, //0
            {data: 'status', name: 'status'}, //1
            {data: 'img', name: 'img'}, //3
            {data: 'idpel', name: 'idpel'}, //3
            {data: 'no_meter', name: 'no_meter'}, //4
            {data: 'nama', name: 'nama'}, //5
            {data: 'alamat', name: 'alamat'}, //6
            {data: 'tarif', name: 'tarif'}, //7
            {data: 'daya', name: 'daya'}, //8
            {data: 'krn', name: 'krn'}, //9
            {data: 'vkrn', name: 'vkrn'}, //10
            {data: 'kct1a', name: 'kct1a'}, //11
            {data: 'kct1b', name: 'kct1b'}, //12
            {data: 'kct2a', name: 'kct2a'}, //13
            {data: 'kct2b', name: 'kct2b'}, //14
            {data: 'kct1', name: 'kct1'}, //15
            {data: 'kct2', name: 'kct2'}, //16
            {data: 'upgraded_at', name: 'upgraded_at'}, //17
            {data: 'confirmed_at', name: 'confirmed_at'}, //18
            {data: 'username', name: 'username'}, //19
        ],
        columnDefs: [
            { visible: false, targets: [ 2, 11, 12, 13, 14, 15, 16, 17, 18, 19] },
            { className: "text-center", targets: [ 0, 1, 3, 4, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19] },
            { searchable: false, targets: [ 0, 4, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18 ] },
            { orderable: false, targets: [ 0, 11, 12, 13, 14 ] }
        ],
        dom:
            "<'row'<'col-lg-12 col-md-12 col-12'B>>" +
            "<'row'<'col-lg-6 col-md-6 col-sm-12 col-12'l><'col-lg-6 col-md-6 col-sm-12 col-12'f>>" +
            "<'row'<'col-lg-12 col-md-12 col-12 table-responsive'tr>>" +
            "<'row'<'col-lg-5 col-md-5 col-12'i><'col-lg-7 col-md-7 col-12'p>>",
        // aoColumns: [
        //     null,
        //     {sClass: "text-center"},
        //     {sClass: "text-center"},
        //     null
        // ],
        buttons: [{
            extend:    'copy',
            text:      '<i class="fa fa-copy"></i>',
            titleAttr: 'Copy',
            className: 'btn btn-info'
        },{
            extend:    'excel',
            text:      '<i class="fa fa-file-excel"></i>',
            titleAttr: 'Excel',
            className: 'btn btn-success'
        },{
            extend:    'pdf',
            text:      '<i class="fa fa-file-pdf"></i>',
            titleAttr: 'PDF',
            className: 'btn btn-danger'
        },{
            extend:    'print',
            text:      '<i class="fa fa-print"></i>',
            titleAttr: 'Print',
            className: 'btn btn-warning',
            exportOptions: {
                columns: ':visible'
            },
            messageTop: 'Spending Data'
        },{
            extend: 'colvis',
            className: 'btn btn-default',
            postfixButtons: ['colvisRestore']
        }],
        language: {
            buttons: {
                colvis: 'Columns'
            },
            searchPlaceholder: "Search records",
            search: ""
        }
    });
    function confirmModal(img,id){
        $('#link').attr('href', img);
        $('#lampiran').attr('src', img);
        $('#xid').val(id);
        $('#modalconfirm').modal('show');
    }
    function confirmUpgrade(mode){
        var id = $('#xid').val();
        $.ajax({
            type: 'POST',
            url: '{{ route('confirmUpgrade') }}',
            data: {
                _token: "{{ csrf_token() }}",
                id: id,
                mode: mode
            },
            dataType: 'json',
            success: function (data) {
                if(data.success == true){
                    $('#modalconfirm').modal('hide');
                    table.ajax.reload();
                    // window.location.replace('{{route("thanksPage")}}');
                }
            },
        });
    }
</script>
@stop
