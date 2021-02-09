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
                <div class="card-header">
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
                </div>
                <div class="card-body">
                    <table class="table" id="spending_table" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Status</th>
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
                            </tr>
                        </thead>
                    </table>
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
    var table = $('#spending_table').DataTable({
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
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'status', name: 'status'},
            {data: 'idpel', name: 'idpel'},
            {data: 'no_meter', name: 'no_meter'},
            {data: 'nama', name: 'nama'},
            {data: 'alamat', name: 'alamat'},
            {data: 'tarif', name: 'tarif'},
            {data: 'daya', name: 'daya'},
            {data: 'krn', name: 'krn'},
            {data: 'vkrn', name: 'vkrn'},
            {data: 'kct1a', name: 'kct1a'},
            {data: 'kct1b', name: 'kct1b'},
            {data: 'kct2a', name: 'kct2a'},
            {data: 'kct2b', name: 'kct2b'}
        ],
        columnDefs: [
            { visible: false, targets: [ 10, 11, 12, 13] },
            { className: "text-center", targets: [ 0, 1, 2, 3, 6, 7, 8, 9, 10, 11, 12, 13] },
            { searchable: false, targets: [ 0, 3, 6, 7, 8, 9, 10, 11, 12 ] },
            { orderable: false, targets: [ 0, 9, 10, 11, 12 ] }
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
</script>
@stop
