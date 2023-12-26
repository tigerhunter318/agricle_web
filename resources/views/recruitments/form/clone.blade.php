@extends('layouts.dashboard')

@section('links')
    @include('css.links')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.css" />
    <style type="text/css">
        img {
            display: block;
            max-width: 100%;
        }
        .modalPreview {
            overflow: hidden;
            width: 300px;
            height: 200px;
            margin: auto;
            border-radius: 10px;
            border: 3px solid #e91e63;
        }
    </style>
@endsection

@section('content')
    <div class="mb-4">
        <h3>{{__('messages.title.recruitment_edit')}}</h3>
    </div>
    <form enctype='multipart/form-data' method="POST" action="{{ route('recruitments.update', $recruitment->id) }}" role="form" autocomplete="off" class="text-start" id="form">
        @csrf
        @method('PUT')
        <input type="hidden" id="clone_status" name="clone_status" value="{{$recruitment['status']}}" />
        <div class="row">
            <div class="col-md-3 col-sm-6 text-center">
                <img src="{{ !empty($recruitment['image']) ? asset('uploads/recruitments/'.$recruitment['image']) : asset('assets/img/utils/empty.png') }}" id="previewImg" class="img-thumbnail img-fluid me-2" style="width: 200px; display: inline;" />
                <div class="m-2">
                    <input type="hidden" id="image" name="image" value="{{ $recruitment['image'] }}">
                    <label class="btn btn-primary btn-sm">
                        <input type="file" id="imageFile" style="display: none" class="form-control" name="imageFile">
                        {{__('messages.image.choose')}}
                    </label>
                    <div>
                        @error('image')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <h6 class="modal-title" id="modal-title-notification"> {{__('messages.alert.confirm')}} </h6>
                            <button type="button" class="btn btn-link my-1" data-bs-dismiss="modal"><i class="fas fa-times"></i></button>
                        </div>
                        <div class="modal-body">
                            <div class="img-container">
                                <div class="row">
                                    <div class="col-6">
                                        <img id="modalImage" class="text-center" src="https://avatars0.githubusercontent.com/u/3456749">
                                    </div>
                                    <div class="col-6">
                                        <div class="modalPreview"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success align-self-end" id="crop">{{__('messages.image.cut')}}</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9 col-sm-6">
                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group input-group-static mt-3">
                            <label>{{__('messages.recruitment.title')}}</label>
                            <input type="text" class="form-control " name="title" value="{{ $recruitment['title'] }}">
                        </div>
                        @error('title')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-12">
                        <div class="input-group input-group-outline mt-3">
                            <textarea name="description" class="form-control" type="text" rows="3" placeholder="{{__('messages.recruitment.description')}}">{{ $recruitment['description'] }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="input-group input-group-static mt-3">
                    <label> {{__('messages.profile.post_number')}} </label>
                    <input type="text" class="form-control" name="post_number" id="post_number" value="{{ $recruitment['post_number'] }}">
                    <span class="input-group-text p-1 z-index-3">
                        <button class="btn btn-primary btn-sm my-0 mx-3" id="get_address_btn" type="button">{{__('messages.action.get_address')}}</button>
                    </span>
                </div>
                @error('post_number')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="input-group input-group-static mt-3">
                    <label> {{__('messages.profile.prefectures')}} </label>
                    <select name="prefectures" id="prefectures" class="form-control">
                        <option value=""></option>
                        @for($k = 0; $k < count(config('global.pref_city')); $k++)
                            <option value="{{config('global.pref_city')[$k]['id']}}" {{ $recruitment['prefectures'] == config('global.pref_city')[$k]['id'] ? "selected" : "" }}>{{config('global.pref_city')[$k]['pref']}}</option>
                        @endfor
                    </select>
                </div>
                @error('prefectures')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="input-group input-group-static mt-3">
                    <label> {{__('messages.profile.city')}} </label>
                    <input type="text" class="form-control" name="city" id="city" value="{{ $recruitment['city'] }}">
                </div>
                @error('city')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="input-group input-group-static mt-3">
                    <label>{{__('messages.recruitment.workplace')}}</label>
                    <input type="text" class="form-control" name="workplace" value="{{ $recruitment['workplace'] }}">
                </div>
                @error('workplace')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="input-group input-group-static mt-3">
                    <label>{{__('messages.recruitment.reward.title')}}</label>
                    <select name="reward_type" id="reward_type" class="form-control">
                        <option value="{{__('messages.recruitment.reward.hourly')}}" {{ $recruitment['reward_type'] == __('messages.recruitment.reward.hourly') ? "selected" : "" }}>{{__('messages.recruitment.reward.hourly')}}</option>
                        <option value="{{__('messages.recruitment.reward.daily')}}" {{ $recruitment['reward_type'] == __('messages.recruitment.reward.daily') ? "selected" : "" }}>{{__('messages.recruitment.reward.daily')}}</option>
                    </select>
                </div>
                @error('reward_type')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="input-group input-group-static mt-3">
                    <label>{{__('messages.recruitment.reward.cost')}}</label>
                    <input type="number" class="form-control" name="reward_cost" value="{{ $recruitment['reward_cost'] }}" step=any min="0">
                </div>
                @error('reward_cost')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <input type="hidden" name="work_date_start" id="work_date_start" value="{{ $recruitment['work_date_start'] }}">
                <input type="hidden" name="work_date_end" id="work_date_end" value="{{ $recruitment['work_date_end'] }}">
                <div class="input-group input-group-static mt-3">
                    <label>{{__('messages.recruitment.work_date')}}</label>
                    <input type="text" class="form-control" name="work_date" id="work_date" value="{{ $recruitment['work_date_start'].' - '.$recruitment['work_date_end'] }}">
                </div>
                @error('work_date_start')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <input type="hidden" name="work_time_start" id="work_time_start" value="{{ $recruitment['work_time_start'] }}">
                <input type="hidden" name="work_time_end" id="work_time_end" value="{{ $recruitment['work_time_end'] }}">
                <div class="input-group input-group-static mt-3">
                    <label>{{__('messages.recruitment.work_time')}}</label>
                    <input type="text" class="form-control" name="work_time" id="work_time" value="{{ $recruitment['work_time_start'].' - '.$recruitment['work_time_end'] }}">
                </div>
                @error('work_time_start', 'work_time_end')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="input-group input-group-static mt-3">
                    <label>{{__('messages.recruitment.break_time')}}</label>
                    <input type="text" class="form-control" name="break_time" id="break_time" value="{{ $recruitment['break_time'] }}">
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="input-group input-group-static mt-3">
                    <label>{{__('messages.recruitment.lunch_mode.title')}}</label>
                    <select name="lunch_mode" id="lunch_mode" class="form-control">
                        <option value="1" {{ $recruitment['lunch_mode'] == '1' ? "selected" : "" }}>{{__('messages.recruitment.lunch_mode.yes')}}</option>
                        <option value="0" {{ $recruitment['lunch_mode'] == '0' ? "selected" : "" }}>{{__('messages.recruitment.lunch_mode.no')}}</option>
                    </select>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="input-group input-group-static mt-3">
                    <label>{{__('messages.recruitment.pay_mode.title')}}</label>
                    <select name="pay_mode" id="pay_mode" class="form-control">
                        <option value="cash" {{ $recruitment['pay_mode'] == 'cash' ? "selected" : "" }}>{{__('messages.recruitment.pay_mode.cash')}}</option>
                        <option value="card" {{ $recruitment['pay_mode'] == 'card' ? "selected" : "" }}>{{__('messages.recruitment.pay_mode.card')}}</option>
                    </select>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="input-group input-group-static mt-3">
                    <label>{{__('messages.recruitment.worker_amount')}}</label>
                    <input type="number" class="form-control" name="worker_amount" value="{{ $recruitment['worker_amount'] }}">
                </div>
                @error('worker_amount')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="input-group input-group-static mt-3">
                    <label>{{__('messages.recruitment.traffic.title')}}</label>
                    <select name="traffic_type" id="traffic_type" class="form-control">
                        <option value="include" {{ $recruitment['traffic_type'] == 'include' ? "selected" : "" }}>{{__('messages.recruitment.traffic.include')}}</option>
                        <option value="beside" {{ $recruitment['traffic_type'] == 'beside' ? "selected" : "" }}>{{__('messages.recruitment.traffic.beside')}}</option>
                    </select>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12" id="traffic_cost_div" style="display: {{ $recruitment['traffic_type'] && $recruitment['traffic_type'] == 'beside' ? 'inline' : 'none' }}">
                <div class="input-group input-group-static mt-3">
                    <label>{{__('messages.recruitment.traffic.cost')}}</label>
                    <input type="number" class="form-control" name="traffic_cost" value="{{ $recruitment['traffic_cost'] }}" step=any min="0">
                </div>
                @error('traffic_cost')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="input-group input-group-static mt-3">
                    <label>{{__('messages.recruitment.rain_mode')}}</label>
                    <input type="text" class="form-control" name="rain_mode" value="{{ $recruitment['rain_mode'] }}">
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="input-group input-group-static mt-3">
                    <label>{{__('messages.recruitment.clothes.title')}}</label>
                    <div id="clothes_select" class="form-control"></div>
                    <input type="hidden" name="clothes" id="clothes" />
                </div>
            </div>

            <div class="col-md-12">
                <div class="input-group input-group-outline mt-3">
                    <textarea name="notice" class="form-control" type="text" rows="3" placeholder="{{__('messages.recruitment.notice')}}">{{ $recruitment['notice'] }}</textarea>
                </div>
            </div>

            <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" id="toilet" {{ $recruitment['toilet'] ? 'checked' : '' }} name="toilet">
                    <label class="form-check-label" for="toilet">{{__('messages.recruitment.toilet.title')}}あり</label>
                </div>
            </div>

            <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" id="park" {{ $recruitment['park'] ? 'checked' : '' }} name="park">
                    <label class="form-check-label" for="park">{{__('messages.recruitment.park.title')}}あり</label>
                </div>
            </div>

            <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" id="insurance" {{ $recruitment['insurance'] ? 'checked' : '' }} name="insurance">
                    <label class="form-check-label" for="insurance">{{__('messages.recruitment.insurance.title')}}</label>
                </div>
            </div>

            <div class="col-12">
                <button type="button" class="btn btn-info m-1" id="to_collecting_btn" onclick="save('collecting')"> <i class="fa fa-users"></i> {{__('messages.recruitment.create')}} </button>
                <button type="button" class="btn btn-success m-1" id="to_draft_btn" onclick="save('draft')"> <i class="fa fa-edit"></i> {{__('messages.action.draft')}} </button>
                <a href="{{route('dashboard')}}" class="btn btn-secondary m-1" > <i class="fa fa-home"></i> {{__('messages.action.home')}} </a>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    @include('scripts.scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script>
        var $modal = $('#imageModal');
        var image = document.getElementById('modalImage');
        var cropper;

        var clothes_select = $('#clothes_select').magicSuggest({
            highlight: false,
            cls: '.clothesSelect',
            placeholder: "{{__('messages.title.select')}}",
            data: [
                "{{__('messages.recruitment.clothes.casual')}}",
                "{{__('messages.recruitment.clothes.hat')}}",
                "{{__('messages.recruitment.clothes.gunte')}}",
                "{{__('messages.recruitment.clothes.gloves')}}",
                "{{__('messages.recruitment.clothes.boots')}}",
                "{{__('messages.recruitment.clothes.rain')}}",
                "{{__('messages.recruitment.clothes.bottle')}}",
            ]
        })
        if("{{$recruitment['clothes']}}") clothes_select.setValue(("{{$recruitment['clothes']}}").split(','));

        $('#work_date').daterangepicker({
            "timePicker": false,
            "showCustomRangeLabel": false,
            "timePicker24Hour": true,
            "applyButtonClasses": "btn-primary mt-3",
            "cancelButtonClasses": "btn-default mt-3",
            "locale": {
                "format": 'YYYY/MM/DD',
                "separator": " - ",
                "applyLabel": "{{__('messages.action.yes')}}",
                "cancelLabel": "{{__('messages.action.no')}}",
                "daysOfWeek": [
                    '日',
                    '月',
                    '火',
                    '水',
                    '木',
                    '金',
                    '土',
                ],
                "monthNames": [
                    "1月",
                    "2月",
                    "3月",
                    "4月",
                    "5月",
                    "6月",
                    "7月",
                    "8月",
                    "9月",
                    "10月",
                    "11月",
                    "12月",
                ],
                "firstDay": 1
            }
        });

        $('#work_time').daterangepicker({
            "timePicker": true,
            "showCustomRangeLabel": false,
            "timePicker24Hour": true,
            "applyButtonClasses": "btn-primary mt-3",
            "cancelButtonClasses": "btn-default mt-3",
            "locale": {
                "format": 'HH:mm',
                "separator": " - ",
                "applyLabel": "{{__('messages.action.yes')}}",
                "cancelLabel": "{{__('messages.action.no')}}",
                "daysOfWeek": [
                    '日',
                    '月',
                    '火',
                    '水',
                    '木',
                    '金',
                    '土',
                ],
                "monthNames": [
                    "1月",
                    "2月",
                    "3月",
                    "4月",
                    "5月",
                    "6月",
                    "7月",
                    "8月",
                    "9月",
                    "10月",
                    "11月",
                    "12月",
                ],
                "firstDay": 1
            }
        }).on('show.daterangepicker', function (ev, picker) {
            picker.container.find(".calendar-table").hide();
        });

        $("body").on("change", "#imageFile", function(e){
            var files = e.target.files;
            var done = function (url) {
                image.src = url;
                $modal.modal({backdrop: 'static', keyboard: false});
                $modal.modal('show');
            };
            var reader;
            var file;
            var url;

            if (files && files.length > 0) {
                file = files[0];

                if (URL) {
                    done(URL.createObjectURL(file));
                } else if (FileReader) {
                    reader = new FileReader();
                    reader.onload = function (e) {
                        done(reader.result);
                    };
                    reader.readAsDataURL(file);
                }
            }
        });

        $modal.on('shown.bs.modal', function () {
            cropper = new Cropper(image, {
                aspectRatio: 1.5,
                viewMode: 3,
                preview: '.modalPreview'
            });
        }).on('hidden.bs.modal', function () {
            cropper.destroy();
            cropper = null;
        });

        $(document).on('click', '#crop', function () {
            canvas = cropper.getCroppedCanvas({
                width: 800,
                height: 600,
            });
            canvas.toBlob(function(blob) {
                url = URL.createObjectURL(blob);
                var reader = new FileReader();
                reader.readAsDataURL(blob);
                reader.onloadend = function() {
                    $("#image").val(reader.result);
                    $("#previewImg").attr("src", reader.result);
                    $modal.modal('hide');
                }
            });
        });

        $("#get_address_btn").click(function(){
            $.ajax({
                url: "https://zipcloud.ibsnet.co.jp/api/search",
                type: "get",
                data: {
                    zipcode : $("#post_number").val(),
                },
                success: function (data) {
                    data = JSON.parse(data);
                    if(data["status"] === 200){
                        $("#prefectures").val(data["results"][0]["prefcode"]);
                        $("#city").val(data["results"][0]["address2"]+" "+data["results"][0]["address3"]);
                    }
                },
                error: function (data, textStatus, errorThrown) {
                    console.log(data);
                },
            });
        });

        function save(status = "{{ $recruitment['status'] }}") {
            $("#to_draft_btn, #to_collecting_btn").attr('disabled', true);

            var work_date_start = $("#work_date").data('daterangepicker').startDate.format('YYYY/MM/DD');
            var work_date_end = $("#work_date").data('daterangepicker').endDate.format('YYYY/MM/DD');

            var work_time_start = $("#work_time").data('daterangepicker').startDate.format('HH:mm');
            var work_time_end = $("#work_time").data('daterangepicker').endDate.format('HH:mm');

            $("#work_date_start").val(work_date_start);
            $("#work_date_end").val(work_date_end);

            $("#work_time_start").val(work_time_start);
            $("#work_time_end").val(work_time_end);

            $("#clothes").val((clothes_select.getValue().join(',')));

            $("#clone_status").val(status);

            $("#form").submit();
        }

        $("#traffic_type").change(function () {
            if($("#traffic_type").val() === "beside") $("#traffic_cost_div").removeAttr('style');
            else if($("#traffic_type").val() === "include") {
                $("#traffic_cost").val(0);
                $("#traffic_cost_div").css('display', 'none');
            }
        });
    </script>
@endsection
