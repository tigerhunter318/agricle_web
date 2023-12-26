@extends('layouts.dashboard')

@section('links')
    @include('css.links')
@endsection

@section('content')
    <div class="mb-4 w-100 w-md-50 w-lg-25">
        <h3>{{__('messages.applicants.result')}}</h3>
    </div>

    <div class="row m-3" id="checkbox-row">
        <div class="col-md-2 col-lg-2 col-sm-6">
            <div class="form-check mt-3">
                <input class="form-check-input" type="checkbox" id="working" name="status" checked onclick="get_result()">
                <label class="form-check-label" for="working">{{__('messages.recruitment.status.working')}}</label>
            </div>
        </div>
        <div class="col-md-2 col-lg-2 col-sm-6">
            <div class="form-check mt-3">
                <input class="form-check-input" type="checkbox" id="completed" name="status" checked onclick="get_result()">
                <label class="form-check-label" for="completed">{{__('messages.recruitment.status.completed')}}</label>
            </div>
        </div>
        <div class="col-md-2 col-lg-2 col-sm-6">
            <div class="form-check mt-3">
                <input class="form-check-input" type="checkbox" id="finished" name="status" checked onclick="get_result()">
                <label class="form-check-label" for="finished">{{__('messages.applicants.status.finished')}}</label>
            </div>
        </div>
        <div class="col-md-2 col-lg-2 col-sm-6">
            <div class="form-check mt-3">
                <input class="form-check-input" type="checkbox" id="abandoned" name="status" checked onclick="get_result()">
                <label class="form-check-label" for="abandoned">{{__('messages.applicants.status.abandoned')}}</label>
            </div>
        </div>
        <div class="col-md-2 col-lg-2 col-sm-6">
            <div class="form-check mt-3">
                <input class="form-check-input" type="checkbox" id="fired" name="status" checked onclick="get_result()">
                <label class="form-check-label" for="fired">{{__('messages.applicants.status.fired')}}</label>
            </div>
        </div>
    </div>

    <div class="row m-3" id="content">
    </div>

@endsection

@section('scripts')
    @include('scripts.scripts')
    <script>
        statuses = [];
        $(document).ready(function(){
            get_result();
        })
        function get_result() {
            statuses = [];
            $('input:checkbox:checked').each(function() {
                statuses.push($(this).attr('id'));
            })
            $.ajax({
                url: "{{ route('get_results') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    statuses: statuses
                },
                dataType: "json",
                success: function (res) {
                    $("#content").empty();
                    if(res.length == 0) $("#content").html('<p class="text-center m-3"> {{__('messages.title.no_data')}} </p>')
                    res.forEach(item => {
                        $("#content").append('\
                            <div class="row">\
                                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4 text-center">\
                                    <img src="{{ asset('uploads/recruitments/') }}/'+item['image']+'" class="img-thumbnail img-fluid me-2" style="height: 100px" />\
                                </div>\
                                <div class="col-lg-10 col-md-9 col-sm-8 col-xs-8">\
                                    <div class="row">\
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">\
                                            '+item['title']+'\
                                            <p class="m-0 text-sm">'+item['description']+'</p>\
                                            <p class="m-0 text-sm">'+item['workplace']+'</p>\
                                            <p class="m-0 text-sm">'+item['work_date_start']+' ~ '+item['work_date_end']+'(<span>'+item['work_time_start']+'~'+item['work_time_end']+'</span>)</p>\
                                            <p class="m-0 text-sm">'+item['notice']+'</p>\
                                        </div>\
                                        <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">\
                                            <p class="m-0 text-sm">'+item['reward']+'</p>\
                                            <p class="m-0 text-sm">'+item['break_time']+'</p>\
                                            <p class="m-0 text-sm">'+"{{__('messages.recruitment.rain_mode')}}"+item['rain_mode']+'</p>\
                                            <p class="m-0 text-sm">'+"{{__('messages.recruitment.clothes.title')}}"+item['clothes']+'</p>\
                                        </div>\
                                        <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12">\
                                            '+item['status']+'\
                                        </div>\
                                    </div>\
                                </div>\
                            </div>\
                        ');
                    })
                },
                error: function (res) {
                    console.log(res);
                }
            })
        }
    </script>
@endsection

