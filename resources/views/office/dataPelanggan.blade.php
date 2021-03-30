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
                                        <div class="col-md-4 col-12">
                                            <label>Status: </label>
                                            <select class="form-control select2bs4" id="status" style="width: 100%;">
                                                <option value="">Semua</option>
                                                <option value="0">Belum diperbaharui</option>
                                                <option value="1">Diperbaharui</option>
                                                <option value="2">Dikonfirmasi</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <label>VKRN: </label>
                                            <select class="form-control select2bs4" id="vkrn" style="width: 100%;">
                                                <option value="">Semua</option>
                                                <option value="41">41</option>
                                                <option value="42">42</option>
                                                <option value="43">43</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <label>PIC: </label>
                                            <select class="form-control select2bs4" id="user" style="width: 100%;">
                                                @if(!empty($user))
                                                    @if($admin)
                                                        {{-- <option value="">Semua</option> --}}
                                                        @foreach($user as $u)
                                                            <option value="{{Crypt::encrypt($u->id)}}">{{$u->name}}</option>
                                                        @endforeach
                                                    @else
                                                        <option value="{{Crypt::encrypt($user->id)}}" selected>{{$user->name}}</option>
                                                    @endif
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Tanggal Input: </label>
                                            <input type="text" id="upgraded_date" class="form-control drp" placeholder="" autocomplete="off">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Tanggal Konfirmasi: </label>
                                            <input type="text" id="confirmed_date" class="form-control drp" placeholder="" autocomplete="off">
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
                                <th class="text-center">Lokasi Input</th>
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
@section('plugins.Select2', true)

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
    $('.select2bs4').select2();
    var upgraded_date = {};
    var confirmed_date = {};
    $('#upgraded_date').daterangepicker({
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate: moment(),
        locale: {
            format: 'YYYY-MM-DD'
        },
	}, applyUpgraded);
    $('#confirmed_date').daterangepicker({
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate: moment(),
        locale: {
            format: 'YYYY-MM-DD'
        },
	}, applyConfirmed);
    function applyUpgraded(start, end){
        upgraded_date.start = start.format('YYYY-MM-DD');
        upgraded_date.end = end.format('YYYY-MM-DD');
	}
    function applyConfirmed(start, end){
        confirmed_date.start = start.format('YYYY-MM-DD');
        confirmed_date.end = end.format('YYYY-MM-DD');
	}

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
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        ajax: {
            url: '{!! route('dataPelangganTable') !!}',
            type: "POST",
            data: function (data) {
                data.status = $('#status').val();
                data.krn = $('#vkrn').val();
                data.user = $('#user').val();
                data.upgraded_date = upgraded_date;
                data.confirmed_date = confirmed_date;
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'}, //0
            {data: 'status', name: 'status'}, //1
            {data: 'img', name: 'img'}, //2
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
            {data: 'location', name: 'location'}, //18
            {data: 'confirmed_at', name: 'confirmed_at'}, //19
            {data: 'username', name: 'username'}, //20
        ],
        columnDefs: [
            { visible: false, targets: [ 2, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20] },
            { className: "text-center", targets: [ 0, 1, 2, 3, 4, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20] },
            { searchable: false, targets: [ 0, 1, 2, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20 ] },
            { orderable: false, targets: [ 0, 11, 12, 13, 14, 18 ] }
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
            messageTop: 'Data Pelanggan'
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
        upgraded_date.start = null;
        upgraded_date.end = null;
        confirmed_date.start = null;
        confirmed_date.end = null;
        $('#form-filter')[0].reset();
        table.ajax.reload();
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
