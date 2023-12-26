@extends('layouts.dashboard')

@section('links')
    @include('css.links')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css">
    <style type="text/css">
        .nounderline, .violet{
            color: #7c4dff !important;
        }
        .btn-dark {
            background-color: #7c4dff !important;
            border-color: #7c4dff !important;
        }
        .btn-dark .file-upload {
            width: 100%;
            padding: 10px 0px;
            position: absolute;
            left: 0;
            opacity: 0;
            cursor: pointer;
        }
        .profile-img img{
            width: 200px;
            height: 200px;
            border-radius: 50%;
        }
    </style>
@endsection

@section('content')
    <div class="m-3">
        <h3>{{__('messages.title.update_profile')}}</h3>
    </div>

    <div class="m-3">
        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-12 text-center">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="profile-img p-3">
                            <img src="{{ $worker['avatar'] === 'default.png' ? asset('assets/img/utils/default.png') : asset('avatars/'.$worker['avatar']) }}" id="profile-pic">
                        </div>
                        <div class="btn btn-dark">
                            <input type="file" class="file-upload" id="file-upload"
                                   name="profile_picture" accept="image/*">
                            {{__('messages.image.choose')}}
                        </div>
                    </div>
                </div>
                <div class="modal" id="myModal">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title">{{__('messages.image.cut_upload')}}</h4>
                                <button type="button" class="btn btn-link my-1" data-bs-dismiss="modal"><i class="fas fa-times"></i></button>
                            </div>
                            <!-- Modal body -->
                            <div class="modal-body">
                                <div id="resizer"></div>
                                <button class="btn rotate float-lef" data-deg="90" >
                                    <i class="fas fa-undo"></i></button>
                                <button class="btn rotate float-right" data-deg="-90" >
                                    <i class="fas fa-redo"></i></button>
                                <hr>
                                <button class="btn btn-block btn-dark" id="upload" >
                                    {{__('messages.action.cut_upload')}}</button>
                            </div>
                        </div>
                    </div>
                </div>

                <input value="{{ $worker['review'] }}" type="text" class="rating" readonly data-size="xs">
            </div>
            <div class="col-lg-8 col-md-6 col-sm-12">
                <div class="row">
                    <div class="col-md-12">
                        <h5 class="font-weight-b$worker[r d-]lex align-items-center">
                            {{ $worker['family_name'] }}
                        </h5>
                        <h6 class="font-weight-b$worker[r d-]lex align-items-center">
                            {{ $worker['email'] }}
                        </h6>
                    </div>
                </div>
                <form role="form" id="form" class="text-start" method="POST" action="{{ route('worker_profile_update') }}" autocomplete="off">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="input-group input-group-static my-1">
                                <label > {{__('messages.profile.name')}} </label>
                                <input type="text" class="form-control" name="family_name" value="{{ $worker['family_name'] }}" />
                                @error('family_name')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="input-group input-group-static my-1">
                                <label > {{__('messages.profile.name_read')}} </label>
                                <input type="text" class="form-control" name="name" value="{{ $worker['name'] }}" />
                                @error('name')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="input-group input-group-static my-1">
                                <label > {{__('messages.profile.nickname')}} </label>
                                <input type="text" class="form-control" name="nickname" value="{{ $worker['nickname'] }}" />
                                @error('nickname')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <div class="input-group input-group-static my-1">
                                <label > {{__('messages.profile.address')}} </label>
                                <input type="text" class="form-control" name="address" value="{{ $worker['address'] }}">
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <div class="input-group input-group-static my-1">
                                <input type="hidden" name="isMan" id="isMan">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="manRadio" {{ $worker['gender'] == 'man' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="manRadio">{{__('messages.profile.gender.man')}}</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="womanRadio" {{ $worker['gender'] == 'woman' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="womanRadio">{{__('messages.profile.gender.woman')}}</label>
                                </div>
                            </div>
                            @error('gender')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <div class="input-group input-group-static my-1">
                                <input class="form-control" type="date" name="birthday" value="{{ $worker['birthday'] }}">
                                @error('birthday')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <div class="input-group input-group-static my-1">
                                <label > {{__('messages.profile.cell_phone')}} </label>
                                <input type="text" class="form-control" name="cell_phone" value="{{ $worker['cell_phone'] }}" />
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <div class="input-group input-group-static my-1">
                                <label > {{__('messages.profile.emergency_phone')}} </label>
                                <input type="text" class="form-control" name="emergency_phone" value="{{ $worker['emergency_phone'] }}" />
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <div class="input-group input-group-static my-1">
                                <label > {{__('messages.profile.emergency_relation')}} </label>
                                <input type="text" class="form-control" name="emergency_relation" value="{{ $worker['emergency_relation'] }}" />
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <div class="input-group input-group-static my-1">
                                <label > {{__('messages.profile.job')}} </label>
                                <input type="text" class="form-control" name="job" value="{{ $worker['job'] }}">
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12">
                            <div class="input-group input-group-static my-3">
                                <label > {{__('messages.profile.bio')}} </label>
                                <textarea type="text" class="form-control" name="bio">{{ $worker['bio'] }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12">
                            <div class="input-group input-group-static my-3">
                                <label > {{__('messages.profile.appeal_point')}} </label>
                                <textarea type="text" class="form-control" name="appeal_point">{{ $worker['appeal_point'] }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12">
                            <button type="button" class="btn bg-gradient-primary my-4 mb-2" onclick="save_profile()"> {{__('messages.action.save')}} </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @include('scripts.scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.js"></script>
    <script>
        var croppie = null;
        var el = document.getElementById('resizer');

        $.base64ImageToBlob = function(str) {
            // extract content type and base64 payload from original string
            var pos = str.indexOf(';base64,');
            var type = str.substring(5, pos);
            var b64 = str.substr(pos + 8);

            // decode base64
            var imageContent = atob(b64);

            // create an ArrayBuffer and a view (as unsigned 8-bit)
            var buffer = new ArrayBuffer(imageContent.length);
            var view = new Uint8Array(buffer);

            // fill the view, using the decoded base64
            for (var n = 0; n < imageContent.length; n++) {
                view[n] = imageContent.charCodeAt(n);
            }

            // convert ArrayBuffer to Blob
            var blob = new Blob([buffer], { type: type });

            return blob;
        }

        $.getImage = function(input, croppie) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    croppie.bind({
                        url: e.target.result,
                    });
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#file-upload").on("change", function(event) {
            $("#myModal").modal('show');
            // Initailize croppie instance and assign it to global variable
            croppie = new Croppie(el, {
                viewport: {
                    width: 200,
                    height: 200,
                    type: 'square'
                },
                boundary: {
                    width: 250,
                    height: 250
                },
                enableOrientation: true
            });
            $.getImage(event.target, croppie);
        });

        $("#upload").on("click", function() {
            croppie.result('base64').then(function(base64) {
                $("#myModal").modal("hide");
                $("#profile-pic").attr("src","{{ asset('assets/img/utils/ajax-loader.gif') }}");

                var url = "{{ route('upload_worker_avatar') }}";
                var formData = new FormData();
                formData.append("profile_picture", $.base64ImageToBlob(base64));

                // This step is only needed if you are using Laravel
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: formData,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        if (data['success']) {
                            $("#profile-pic").attr("src", base64);
                        } else {
                            $("#profile-pic").attr("src", "{{ $worker['avatar'] === 'default.png' ? asset('assets/img/utils/default.png') : asset('avatars/'.$worker['avatar']) }}");
                            console.log(data);
                        }
                    },
                    error: function(error) {
                        console.log(error);
                        $("#profile-pic").attr("src","{{ $worker['avatar'] === 'default.png' ? asset('assets/img/utils/default.png') : asset('avatars/'.$worker['avatar']) }}");
                    }
                });
            });
        });

        // To Rotate Image Left or Right
        $(".rotate").on("click", function() {
            croppie.rotate(parseInt($(this).data('deg')));
        });

        $('#myModal').on('hidden.bs.modal', function (e) {
            // This function will call immediately after model close
            // To ensure that old croppie instance is destroyed on every model close
            setTimeout(function() { croppie.destroy(); }, 100);
        })

        function save_profile() {
            $("#isMan").val(document.getElementById('manRadio').checked)
            var formData = $("#form").serializeArray();
            console.log(formData);
            $.ajax({
                url: "{{ route('worker_profile_update') }}",
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'put',
                dataType: 'json',
                success: function (res) {
                    if(res.success) {
                        $.toast({
                            heading: '{{__('messages.alert.success')}}',
                            text: '{{__('messages.profile.saved')}}',
                            position: 'top-right',
                            icon: 'success'
                        })
                    }
                },
                error: function (res) {
                    console.log(res)
                    if(res.responseJSON && res.responseJSON.errors) {
                        for(var key in res.responseJSON.errors) {
                            $.toast({
                                heading: '{{__('messages.alert.error')}}',
                                text: res.responseJSON.errors[key],
                                position: 'top-right',
                                showHideTransition: 'fade',
                                icon: 'error'
                            })
                        }
                    }
                }
            })
        }
    </script>
@endsection

