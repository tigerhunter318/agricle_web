@extends('layouts.dashboard')

@section('links')
    @include('css.links')
    <link href='{{ asset('assets/plugins/calendar/main.css') }}' rel='stylesheet' />
@endsection

@section('content')
    <div class="mb-4">
        <h3>{{__('messages.title.messages')}}</h3>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-12">
            <div class="list-group" id="messageType">
                <a href="#" class="list-group-item list-group-item-action d-flex align-items-center active" id="all">
                    <i class="material-icons me-3">email</i>
                    {{__("messages.messages.all")}}
                </a>
                <a href="#" class="list-group-item list-group-item-action d-flex align-items-center" id="receive">
                    <i class="material-icons me-3">forward_to_inbox</i>
                    {{__("messages.messages.receive")}}
                </a>
                <a href="#" class="list-group-item list-group-item-action d-flex align-items-center" id="send">
                    <i class="material-icons me-3">move_to_inbox</i>
                    {{__("messages.messages.send")}}
                </a>
            </div>
        </div>
        <div class="col-lg-9 col-md-9 col-sm-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="input-group input-group-outline w-40">
                    <label class="form-label"> {{__('messages.messages.keyword')}} </label>
                    <input type="text" class="form-control" name="keyword" id="keyword">
                    <span class="input-group-text p-1 z-index-3">
                        <button class="btn btn-primary btn-sm my-0 mx-1" onclick="search_messages()">{{__('messages.action.search')}}</button>
                    </span>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="unreadCheckbox">
                    <label class="custom-control-label" for="customCheck1">{{__('messages.messages.unread')}}</label>
                </div>
            </div>
            <div id="messageList"></div>
        </div>
    </div>
@endsection

@section('scripts')
    @include('scripts.scripts')
    <script>
        const data = {};

        $(document).ready(function(){
            search_messages();
        });

        $("#messageType .list-group-item").click(function(){
            $('.list-group-item').removeClass("active");
            $(this).addClass('active');
            data['type'] = this.id;
            $("#keyword").val('');
            delete data['keyword'];
            search_messages();
        })

        $("#unreadCheckbox").change(function(){
            data['unread'] = this.checked;
            if(!this.checked) delete data['unread'];
            search_messages();
        })

        $("#keyword").on('keyup', function() {
            data['keyword'] = this.value;
            if(!this.value) delete data['keyword'];
        });

        function search_messages() {
            $.ajax({
                url: "{{ route('search_messages') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                data,
                success: function (response) {
                    $("#messageList").html(response);
                }
            });
        }
    </script>
@endsection
