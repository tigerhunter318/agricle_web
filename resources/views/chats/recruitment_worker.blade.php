@extends('layouts.dashboard')

@section('links')
    @include('css.links')
@endsection

@section('content')
    <h3>{{__('messages.chatting.title')}}</h3>

{{--    <x-recruitment :recruitment="$recruitment" :count="null"/>--}}

    <button type="button" class="btn btn-secondary mb-5" onclick="javascript:history.go(-1);"> <i class="fa fa-arrow-left"></i>
        {{__('messages.action.back')}} </button>
    <div class="row">
        <div class="col-lg-12">
            <div class="card max-height-vh-70" id="card">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent" id="card-header">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg p-3">
                        <div class="row">
                            <div class="col-md-11">
                                <div class="d-flex align-items-center">
                                    <img alt="Image" src="{{ $producer['avatar'] === 'default.png' ? asset('assets/img/utils/default.png') : asset('avatars/'.$producer['avatar']) }}" class="avatar shadow">
                                    <div class="ms-3">
                                        <h6 class="mb-0 d-block text-white">{{ $producer['family_name'] }}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-1 my-auto">
                                <div class="dropdown">
                                    <button class="btn btn-icon-only text-white mb-0" type="button" data-bs-toggle="modal" data-bs-target="#clearMessageModal">
                                        <i class="material-icons">delete</i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body overflow-auto overflow-x-hidden" id="card-body">
                </div>
                <div class="card-footer d-block" id="card-footer">
                    <div class="input-group input-group-outline d-flex">
                        <label class="form-label">{{__('messages.chatting.enter_message')}}</label>
                        <input type="hidden" id="receiver_id" value="{{ $producer['id'] }}">
                        <input type="text" class="form-control form-control-lg" name="message" id="message">
                        <button class="btn bg-gradient-primary mb-0" onclick="send_message()">
                            <i class="material-icons">send</i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="clearMessageModal" tabindex="-1" aria-labelledby="clearMessageModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-" role="document">
            <div class="modal-content bg-gradient-info">
                <div class="modal-header border-0">
                    <h6 class="modal-title text-white" id="modal-title-notification"> {{__('messages.alert.confirm')}} </h6>
                    <button type="button" class="btn btn-link text-white my-1" data-bs-dismiss="modal"><i class="fas fa-times"></i></button>
                </div>
                <hr class="horizontal light mt-0">
                <div class="modal-body">
                    <div class="py-3 text-center text-white">
                        <h4 class="heading mt-4 text-white">{{__('messages.chatting.clear_message')}}</h4>
                    </div>
                </div>
                <hr class="horizontal light mb-0">
                <div class="modal-footer justify-content-between border-0">
                    <button type="button" class="btn btn-success my-1" data-bs-dismiss="modal" onclick="clear_message()">
                        {{__('messages.action.yes')}}</button>
                    <button type="button" class="btn btn-danger my-1" data-bs-dismiss="modal">{{__('messages.action.no')}}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @include('scripts.scripts')
    <script src="https://js.pusher.com/7.0.3/pusher.min.js"></script>

    <script>
        var receiver_id;
        var skip = 0;
        var date = '';

        var pusher = new Pusher("{{env('PUSHER_APP_KEY')}}", {
            cluster: "ap3",
        });

        function linkify(inputText) {
            var replacedText, replacePattern1, replacePattern2, replacePattern3;

            //URLs starting with http://, https://, or ftp://
            replacePattern1 = /(\b(https?|ftp):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/gim;
            replacedText = inputText.replace(replacePattern1, '<a href="$1" target="_blank"><span class="bg-white text-info">$1</span></a>');

            //URLs starting with "www." (without // before it, or it'd re-link the ones done above).
            replacePattern2 = /(^|[^\/])(www\.[\S]+(\b|$))/gim;
            replacedText = replacedText.replace(replacePattern2, '$1<a href="http://$2" target="_blank"><span class="bg-white text-info">$2</span></a>');

            //Change email addresses to mailto:: links.
            replacePattern3 = /(([a-zA-Z0-9\-\_\.])+@[a-zA-Z\_]+?(\.[a-zA-Z]{2,6})+)/gim;
            replacedText = replacedText.replace(replacePattern3, '<a href="mailto:$1"><span class="bg-white text-info">$1</span></a>');

            return replacedText;
        }

        var chat = pusher.subscribe("chat");

        chat.bind("receive-{{Auth::user()->id}}", (data) => {
            if(data['recruitment_id'] !== "{{$recruitment['recruitment_id']}}") return;
            $.ajax({
                url: "{{ route('set_read') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                data: {
                    message_id: data['message_id']
                },
            });

            var message_time = ParseDate(data['created_at'], 'time');

            $("#card-body").append(
                '<div class="row justify-content-start mb-4" id="message-'+data['message_id']+'">\
                    <div class="col-auto">\
                        <div class="card text-dark">\
                            <div class="card-body p-2">\
                                <p class="mb-1">'+linkify(data['message'])+'<br></p>\
                                <div class="d-flex align-items-center justify-content-end text-sm opacity-6">\
                                    <i class="material-icons text-sm me-1">person</i>\
                                    <small class="me-3">'+data['sender']['family_name']+'</small>\
                                    <i class="material-icons text-sm me-1">done_all</i>\
                                    <small>'+message_time+'</small>\
                                </div>\
                            </div>\
                        </div>\
                    </div>\
                </div>\
            ');
            $('#card-body').scrollTop($('#card-body')[0].scrollHeight);
            skip++;
        });

        chat.bind("send-{{Auth::user()->id}}", (data) => {
            if(data['recruitment_id'] !== "{{$recruitment['recruitment_id']}}") return;
            var message_time = ParseDate(data['created_at'], 'time');

            var message = $("#card-body").find('#'+data['element_id'])[0];
            $("#card-body").find('#'+data['element_id']).attr('id', 'message-'+data['message_id']).find('.card-body').append(
                '<div class="d-flex align-items-center justify-content-end text-sm opacity-6">\
                    <i class="material-icons text-sm me-1">person</i>\
                    <small class="me-3">'+data['sender']['family_name']+'</small>\
                    <i class="material-icons text-sm me-1">schedule</i>\
                    <small>'+message_time+'</small>\
                </div>'
            )
        });

        chat.bind("read-message", message_id => {
            $('#card-body').find("#message-"+message_id).find('i:last').text('done_all');
        })

        $(document).ready(function () {
            get_message($("#receiver_id").val());
        });

        $(document).on('keypress', '#message', function (e) {
            if(e.which === 13) {
                send_message();
            }
        })

        function send_message() {
            if(!$("#message").val()) return;

            $.ajax({
                url: "{{ route('send_message') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                dataType: 'json',
                data: {
                    message: $('#message').val(),
                    receiver_id: $("#receiver_id").val(),
                    recruitment_id: "{{ $recruitment['recruitment_id'] }}",
                    element_id: 'new-'+skip,
                },
            });

            $("#card-body").append(
                '<div class="row justify-content-end text-right mb-4" id="new-'+skip+'">\
                    <div class="col-auto">\
                        <div class="card bg-gradient-primary text-white">\
                            <div class="card-body p-2">\
                                <p class="mb-1">'+linkify($("#message").val())+'<br></p>\
                            </div>\
                        </div>\
                    </div>\
                </div>'
            )
            $('#card-body').scrollTop($('#card-body')[0].scrollHeight);
            skip++;

            $("#message").val('');
        }

        function clear_message() {
            $.ajax({
                url: "{{ route('clear_message') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    receiver_id: $("#receiver_id").val(),
                    recruitment_id: "{{ $recruitment['recruitment_id'] }}",
                },
                dataType: 'json',
                type: 'POST',
                success: function (res) {
                    if(res.success) $("#card-body").empty();
                },
                error: function (res) {
                    console.log(res);
                }
            });
        }

        function get_message(user_id) {
            $.ajax({
                url: "{{ route('get_message') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'GET',
                data: {
                    user_id: user_id,
                    skip: skip,
                    recruitment_id: "{{ $recruitment['recruitment_id'] }}"
                },
                dataType: 'json',
                success:function(messages)
                {
                    if(skip === 0) $("#card-body").empty();
                    messages.forEach(message => {
                        var message_date = ParseDate(message['created_at'], 'date');
                        var message_time = ParseDate(message['created_at'], 'time');
                        if(message_date !== date) {
                            if(date !== '') {
                                $("#card-body").prepend(
                                    '<div class="row mt-4">\
                                        <div class="col-md-12 text-center">\
                                            <span class="badge text-dark">'+date+'</span>\
                                    </div>\
                                </div>'
                                );
                            }
                            date = message_date;
                        }
                        var rowClass = [];
                        if(message['sender_id'] === {{ Auth::user()->id }}) {
                            rowClass[0] = "row justify-content-end text-right mb-4"
                            rowClass[1] = "bg-gradient-primary text-white"
                        }
                        else {
                            rowClass[0] = "row justify-content-start mb-4"
                            rowClass[1] = "text-dark"
                        }
                        $("#card-body").prepend(
                            '<div class="'+rowClass[0]+'" id="message-'+message['message_id']+'">\
                                <div class="col-auto">\
                                    <div class="card '+rowClass[1]+'">\
                                        <div class="card-body p-2">\
                                            <p class="mb-1">'+linkify(message['message'])+'<br></p>\
                                            <div class="d-flex align-items-center justify-content-end text-sm opacity-6">\
                                                <i class="material-icons text-sm me-1">person</i>\
                                                <small class="me-3">'+message['sender']['family_name']+'</small>\
                                                <i class="material-icons text-sm me-1">'+(message['read']?'done_all':'schedule')+'</i>\
                                                <small>'+message_time+'</small>\
                                            </div>\
                                        </div>\
                                    </div>\
                                </div>\
                            </div>\
                    ')
                    })
                    $("#card").show();
                    if(messages.length) {
                        $('#card-body').scrollTop($('#card-body').scrollTop() + $('#message-'+messages[0]['message_id']).position().top);
                    }
                    skip += messages.length;
                },
                error: function(res) {
                    console.log('error', res);
                }
            });
        }

        function ParseDate(datetime, format) {
            if(format === 'date') {
                var year = new Date(datetime).getFullYear();
                var month = new Date(datetime).getMonth() + 1;
                month = ('0' + month).slice(-2)
                var date = new Date(datetime).getDate();
                date = ('0' + date).slice(-2)
                return year + '-' + month + '-' + date;
            }
            else {
                var hour = new Date(datetime).getHours();
                var minute = new Date(datetime).getMinutes();
                minute = ('0' + minute).slice(-2)
                var second = new Date(datetime).getSeconds();
                second = ('0' + second).slice(-2)
                return hour + ':' + minute + ':' + second;
            }
        }

    </script>
@endsection
