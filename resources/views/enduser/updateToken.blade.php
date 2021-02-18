@extends('adminlte::enduser')

@section('adminlte_css_pre')
@stop

@section('auth_header')
    <p class="text-center">Update Token</p>
@stop

@section('auth_body')
    {{-- <form action="{{ route('detailPel') }}" method="post">
        {{ csrf_field() }} --}}

        {{-- Email field --}}
    <div class="kct1">
        <div class="row">
            <div class="col-12">
                <p>Masukkan nomor token berikut secara berurutan dan tekan enter di setiap nomor tokennya.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label>KCT1</label>
                    <input type="text" class="form-control" value="{{ $pel->kct1a }}" disabled>
                </div>
                <div class="form-group">
                    <label>KCT2</label>
                    <input type="text" class="form-control" value="{{ $pel->kct1b }}" disabled>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <p>Pastikan semua token terisi dengan benar, lalu perhatikan layar KWH meter Anda.<br>
                    Tekan tombol dibawah sesuai dengan pesan yang ada di layar KWH meter.
                </p>
            </div>
        </div>
    </div>
    <div class="kct2">
        <div class="row">
            <div class="col-12">
                <p>Masukkan kembali nomor token berikut secara berurutan dan tekan enter di setiap nomor tokennya.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                @if(!empty($pel->kct2a))
                <div class="form-group">
                    <label>KCT3</label>
                    <input type="text" class="form-control" value="{{ $pel->kct2a }}" disabled>
                </div>
                @endif
                @if(!empty($pel->kct2b))
                <div class="form-group">
                    <label>KCT4</label>
                    <input type="text" class="form-control" value="{{ $pel->kct2b }}" disabled>
                </div>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <p>Pastikan semua token terisi dengan benar, lalu perhatikan layar KWH meter Anda.<br>
                    Tekan tombol dibawah sesuai dengan pesan yang ada di layar KWH meter.
                </p>
            </div>
        </div>
    </div>
    <div class="konfirmasi">
        <div class="row">
            <div class="col-12">
                <p>Tekan angka 04 pada KWH meter Anda, lalu foto layar KWH meter Anda dan upload ke sistem.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                {{-- <form action="{{ route('submitUpgrade') }}" method="post" id="formSubmit"> --}}
                    {{-- {{ csrf_field() }} --}}
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="berkas" onchange="generateBase64()" required>
                        <label class="custom-file-label" for="berkas">Choose file</label>
                    </div>
                    <div id="warning" style="color: rgb(255, 0, 0);"><span class="fa fa-exclamation-triangle"></span> Silahkan upload gambar terlebih dahulu!</div>
                    <input type="hidden" id="img">
                    <input type="hidden" name="id" value="{{ Crypt::encrypt($pel->id) }}">
                    <img id="thumbnail" class="mx-auto">
                {{-- </form> --}}
            </div>
        </div>
    </div>
    {{-- </form> --}}
@stop

@section('auth_footer')
    <div class="kct1">
        <a href="{{ route('idForm') }}" class="btn btn-danger">Salah</a>
        @if(!empty($pel->kct2a) && !empty($pel->kct2b))
        <button class="btn btn-success float-right" onclick="goToKct2()">Benar</button>
        @else
        <button class="btn btn-success float-right" onclick="openConfirm()">Benar</button>
        @endif
    </div>
    <div class="kct2">
        <button class="btn btn-danger" onclick="">Salah</button>
        <button class="btn btn-success float-right" onclick="setKct2()">Benar</button>
    </div>
<div class="konfirmasi">
        <button class="btn btn-default" onclick="getBack()">Kembali</button>
        <button class="btn btn-success float-right" onclick="submitData()">Kirim</button>
    </div>
@stop

@section('js')
<script src="/js/loading-overlay.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.konfirmasi').hide();
        $('.kct2').hide();
        $('#warning').hide();
    });
    function goToKct2(){
        var id = '{{ Crypt::encrypt($pel->id) }}';
        $.ajax({
            type: 'POST',
            url: '{{ route('kctStatus') }}',
            data: {
                _token: "{{ csrf_token() }}",
                id: id,
                kct1: 1
            },
            dataType: 'json',
            success: function (data) {
                if(data.success == true){
                    $('.kct1').slideUp();
                    $('.kct2').slideDown();
                }
            },
        });
    }
    function setKct2(){
        var id = '{{ Crypt::encrypt($pel->id) }}';
        $.ajax({
            type: 'POST',
            url: '{{ route('kctStatus') }}',
            data: {
                _token: "{{ csrf_token() }}",
                id: id,
                kct2: 1
            },
            dataType: 'json',
            success: function (data) {
                if(data.success == true){
                    openConfirm();
                }
            },
        });
    }
    function openConfirm(){
        $('.kct1').slideUp();
        $('.kct2').slideUp();
        $('.konfirmasi').slideDown();
    }
    function getBack(){
        $('.konfirmasi').slideUp();
        $('.kct1').slideDown();
    }
    function submitData(){
        var lat
        var long

        if ("geolocation" in navigator){ //check geolocation available
            //try to get user current location using getCurrentPosition() method
            navigator.geolocation.getCurrentPosition(function(position){
                lat = position.coords.latitude;
                long = position.coords.longitude;
            });
        }

        var id = '{{ Crypt::encrypt($pel->id) }}';
        var img = $('#img').val();

        if(img == '' || img == null){
            $('#warning').show().fadeOut(5000);
        }else{
            $('body').LoadingOverlay('show');
            $.ajax({
                type: 'POST',
                url: '{{ route('submitUpgrade') }}',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    img: img,
                    lat: lat,
                    long: long
                },
                dataType: 'json',
                success: function (data) {
                    if(data.success == true){
                        window.location.replace('{{route("thanksPage")}}');
                    }
                },
            });
        }
    }
    function generateBase64(){
        var fileReader = new FileReader();
        var filterType = /^(?:image\/bmp|image\/cis\-cod|image\/gif|image\/ief|image\/jpeg|image\/jpg|image\/JPG|image\/JPEG|image\/pipeg|image\/png|image\/PNG|image\/svg\+xml|image\/tiff|image\/x\-cmu\-raster|image\/x\-cmx|image\/x\-icon|image\/x\-portable\-anymap|image\/x\-portable\-bitmap|image\/x\-portable\-graymap|image\/x\-portable\-pixmap|image\/x\-rgb|image\/x\-xbitmap|image\/x\-xpixmap|image\/x\-xwindowdump)$/i;
        var uploadImage = document.getElementById("berkas");
        if (uploadImage.files.length === 0) {
            return;
        }
        var uploadFile = document.getElementById("berkas").files[0];
        if (!filterType.test(uploadFile.type)) {
            alert("Please select a valid image.");
            return;
        }
        fileReader.readAsDataURL(uploadFile);
        fileReader.addEventListener('load', function (){
            var image = new Image();
            image.onload=function(){
                var max_h = 1000;
                var max_w = 1000;
                var thumb = 300;
                var w = image.width;
                var h = image.height;
                var t_w = image.width;
                var t_h = image.height;
                if(w > max_w){
                    h*=max_w/w;
                    w=max_w;
                }
                if(t_w > thumb){
                    t_h*=thumb/t_w;
                    t_w=thumb;
                }
                if(h > max_h){
                    w*=max_h/h;
                    h=max_h;
                }
                if(t_h > thumb){
                    t_w*=thumb/t_h;
                    t_h=thumb;
                }
                var canvas = document.createElement('canvas');
                canvas.width = w;
                canvas.height = h;
                canvas.getContext('2d').drawImage(image, 0, 0, w, h);
                var t_canvas = document.createElement('canvas');
                t_canvas.width = t_w;
                t_canvas.height = t_h;
                t_canvas.getContext('2d').drawImage(image, 0, 0, t_w, t_h);
                var dataURL = canvas.toDataURL("image/png");
                var t_dataURL = t_canvas.toDataURL("image/png");
                document.getElementById("thumbnail").src = t_dataURL;
                document.getElementById("img").value = dataURL;
            }
            image.src=event.target.result;
        });
    }
</script>
@stop
