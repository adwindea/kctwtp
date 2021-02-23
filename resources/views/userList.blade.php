@extends('adminlte::page')

@section('title', 'Data Pengguna')

@section('content_header')
    <h1>Data Pengguna</h1>
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
                                        <div class="col-md-4 col-12">
                                            <label>Status: </label>
                                            <select class="form-control select2bs4" id="status" style="width: 100%;">
                                                <option value="">Semua</option>
                                                <option value="0">Tidak Aktif</option>
                                                <option value="1">Aktif</option>
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
                    <table class="table" id="tabel_pengguna" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Status</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th class="text-center">Tanggal Dibuat</th>
                                <th class="text-center">Action</th>
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
                    <h4 class="modal-title">Aktivasi Pengguna</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah anda yakin untuk aktivasi <span id="user_name"></span>?
                </div>
                <div class="modal-footer justify-content-between">
                    <input type="hidden" id="user_id">
                    <button type="button" class="btn btn-success" onclick="aktivasi()">Aktivasi</button>
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
<link href="/assets/daterangepicker/daterangepicker.css" rel="stylesheet">
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
<script src="/assets/daterangepicker/moment.min.js"></script>
<script src="assets/daterangepicker/daterangepicker.js"></script>

<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var table = $('#tabel_pengguna').DataTable({
        processing: true,
        serverSide: true,
        order: [],
        scrollX: true,
        stateSave: true,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        ajax: {
            url: '{!! route('userListTable') !!}',
            type: "POST",
            data: function (data) {
                data.status = $('#status').val();
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'}, //0
            {data: 'status', name: 'status'}, //1
            {data: 'name', name: 'name'}, //2
            {data: 'email', name: 'email'}, //3
            {data: 'created_at', name: 'created_at'}, //4
            {data: 'action', name: 'action'}, //5
        ],
        columnDefs: [
            { visible: false, targets: [ ] },
            { className: "text-center", targets: [ 0, 1, 4, 5 ] },
            { searchable: false, targets: [ 0, 1, 4, 5 ] },
            { orderable: false, targets: [ 0, 5 ] }
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
            messageTop: 'Data Pengguna'
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
    $('#btn-filter').click(function(){
        table.ajax.reload();
        initDate();
    });
    $('#btn-reset').click(function(){
        $('#form-filter')[0].reset();
        table.ajax.reload();
    });
    function confirmModal(name,id){
        $('#user_name').html(name);
        $('#user_id').val(id);
        $('#modalconfirm').modal('show');
    }
    function aktivasi(){
        var id = $('#user_id').val();
        $.ajax({
            type: 'POST',
            url: '{{ route('userActivation') }}',
            data: {
                _token: "{{ csrf_token() }}",
                id: id
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
