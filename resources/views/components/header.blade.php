<nav class="navbar navbar-expand-lg navbar-light bg-white py-3">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}" rel="tooltip" title="Designed and Coded by Creative Tim" data-placement="bottom">
            <h6>{{ env('APP_NAME') }}</h6>
        </a>
        <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon mt-2">
          <span class="navbar-toggler-bar bar1"></span>
          <span class="navbar-toggler-bar bar2"></span>
          <span class="navbar-toggler-bar bar3"></span>
        </span>
        </button>
        <div class="collapse navbar-collapse w-100 pt-3 pb-2 py-lg-0" id="navigation">
            @if(Auth::user()->role === 'producer')
                <ul class="navbar-nav navbar-nav-hover ms-auto">
                    <li class="nav-item ms-lg-auto">
                        <a class="nav-link nav-link-icon me-2 text-nowrap" href="{{ route('recruitments.create') }}">
                            <i class="fa fa-pencil me-1"></i>
                            <span class="text-nowrap">{{__('messages.header.recruitment_create')}}</span>
                        </a>
                    </li>
                    <li class="nav-item dropdown dropdown-hover mx-2 ">
                        <a class="nav-link ps-2 d-flex justify-content-start cursor-pointer align-items-center text-nowrap" id="recruitmentsMenu" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-tags me-1"></i>
                            <span class="text-nowrap">{{__('messages.header.recruitment_status.title')}}</span>
                            <img src="{{ asset('assets/img/down-arrow-dark.svg') }}" alt="down-arrow" class="arrow ms-auto ms-md-2">
                        </a>
                        <div class="dropdown-menu ms-n3 dropdown-menu-animation dropdown-md p-3 border-radius-lg mt-0 mt-lg-3" aria-labelledby="recruitmentsMenu">
                            <div class="d-none d-lg-block">
                                <a href="{{ route('recruitment_status_view', ['status' => 'draft']) }}" class="dropdown-item border-radius-md">
                                    <span>{{__('messages.header.recruitment_status.draft')}}</span>
                                </a>
                                <a href="{{ route('recruitment_status_view', ['status' => 'collecting']) }}" class="dropdown-item border-radius-md">
                                    <span>{{__('messages.header.recruitment_status.collecting')}}</span>
                                </a>
                                <a href="{{ route('recruitment_status_view', ['status' => 'working']) }}" class="dropdown-item border-radius-md">
                                    <span>{{__('messages.header.recruitment_status.working')}}</span>
                                </a>
                                <a href="{{ route('recruitment_status_view', ['status' => 'completed']) }}" class="dropdown-item border-radius-md">
                                    <span>{{__('messages.header.recruitment_status.completed')}}</span>
                                </a>
                                <a href="{{ route('recruitment_status_view', ['status' => 'canceled']) }}" class="dropdown-item border-radius-md">
                                    <span>{{__('messages.header.recruitment_status.canceled')}}</span>
                                </a>
                                <a href="{{ route('recruitment_status_view', ['status' => 'deleted']) }}" class="dropdown-item border-radius-md">
                                    <span>{{__('messages.header.recruitment_status.deleted')}}</span>
                                </a>
                            </div>
                            <div class="d-lg-none">
                                <a href="{{ route('recruitment_status_view', ['status' => 'draft']) }}" class="dropdown-item border-radius-md">
                                    <span>{{__('messages.header.recruitment_status.draft')}}</span>
                                </a>
                                <a href="{{ route('recruitment_status_view', ['status' => 'collecting']) }}" class="dropdown-item border-radius-md">
                                    <span>{{__('messages.header.recruitment_status.collecting')}}</span>
                                </a>
                                <a href="{{ route('recruitment_status_view', ['status' => 'working']) }}" class="dropdown-item border-radius-md">
                                    <span>{{__('messages.header.recruitment_status.working')}}</span>
                                </a>
                                <a href="{{ route('recruitment_status_view', ['status' => 'completed']) }}" class="dropdown-item border-radius-md">
                                    <span>{{__('messages.header.recruitment_status.completed')}}</span>
                                </a>
                                <a href="{{ route('recruitment_status_view', ['status' => 'canceled']) }}" class="dropdown-item border-radius-md">
                                    <span>{{__('messages.header.recruitment_status.canceled')}}</span>
                                </a>
                                <a href="{{ route('recruitment_status_view', ['status' => 'deleted']) }}" class="dropdown-item border-radius-md">
                                    <span>{{__('messages.header.recruitment_status.deleted')}}</span>
                                </a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown dropdown-hover mx-2">
                        <a class="nav-link ps-2 d-flex justify-content-start cursor-pointer align-items-center text-nowrap" id="chatMenu" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-comments me-1"></i>
                            <span class="text-nowrap">{{__('messages.header.chatting.title')}}</span>
                            <img src="{{ asset('assets/img/down-arrow-dark.svg') }}" alt="down-arrow" class="arrow ms-auto ms-md-2">
                        </a>
                        <div class="dropdown-menu ms-n3 dropdown-menu-animation dropdown-md p-3 border-radius-lg mt-0 mt-lg-3" aria-labelledby="chatMenu">
                            <div class="d-none d-lg-block">
                                <a href="{{ route('chat_recruitments_view') }}" class="dropdown-item border-radius-md">
                                    <span>{{__('messages.header.chatting.favourites')}}</span>
                                </a>
                                <a href="{{ route('favourites_chat') }}" class="dropdown-item border-radius-md">
                                    <span>{{__('messages.header.chatting.free')}}</span>
                                </a>
                            </div>
                            <div class="d-lg-none">
                                <a href="{{ route('chat_recruitments_view') }}" class="dropdown-item border-radius-md">
                                    <span>{{__('messages.header.chatting.favourites')}}</span>
                                </a>
                                <a href="{{ route('favourites_chat') }}" class="dropdown-item border-radius-md">
                                    <span>{{__('messages.header.chatting.free')}}</span>
                                </a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown dropdown-hover mx-2" id="producer_notification_item">
                        <a class="nav-link ps-2 d-flex justify-content-start cursor-pointer align-items-center position-relative" data-bs-toggle="dropdown" aria-expanded="false">
                            <h6><i class="fa fa-bell"></i></h6>
                            <span class="position-absolute top-5 start-0 translate-middle badge rounded-pill bg-danger border border-white small py-1 px-2" style="display: none">
                                <span class="small" id="producer_notification_count">0</span>
                            </span>
                        </a>
                        <div class="dropdown-menu ms-n3 dropdown-menu-animation dropdown-md p-3 border-radius-lg mt-0 mt-lg-3 overflow-auto overflow-x-hidden max-height-250" aria-labelledby="notificationMenu" id="producer_notification_body" style="">
                            <a class="dropdown-item border-radius-md empty">
                                <div class="text-center">
                                    <i class="fa fa-exclamation-triangle" style="font-size: 40px"></i>
                                    <p class="text-center text-bold m-0"> {{__('messages.header.no_news')}} </p>
                                </div>
                            </a>
                            <a class="dropdown-item border-radius-md read-more" href="{{route('news_view')}}">
                                <p class="text-center m-0">
                                    <i class="fa fa-arrow-right"></i>
                                    {{__('messages.action.read_more')}}
                                </p>
                            </a>
                        </div>
                    </li>
                    <li class="nav-item dropdown dropdown-hover mx-2" id="producer_message_item">
                        <a class="nav-link ps-2 d-flex justify-content-start cursor-pointer align-items-center position-relative" data-bs-toggle="dropdown" aria-expanded="false">
                            <h6><i class="fa fa-envelope"></i></h6>
                            <span class="position-absolute top-5 start-0 translate-middle badge rounded-pill bg-danger border border-white small py-1 px-2" style="display: none">
                                <span class="small" id="producer_message_count">0</span>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end ms-n3 dropdown-menu-animation dropdown-md p-3 border-radius-lg mt-0 mt-lg-3 overflow-auto overflow-x-hidden max-height-250" aria-labelledby="messageMenu" id="producer_message_body" style="">
                            <a class="dropdown-item border-radius-md empty">
                                <div class="text-center">
                                    <i class="fa fa-exclamation-triangle" style="font-size: 40px"></i>
                                    <p class="text-center text-bold m-0"> {{__('messages.header.no_msg')}} </p>
                                </div>
                            </a>
                            <a class="dropdown-item border-radius-md read-more" href="{{route('messages_view')}}">
                                <p class="text-center m-0">
                                    <i class="fa fa-arrow-right"></i>
                                    {{__('messages.action.read_more')}}
                                </p>
                            </a>
                        </div>
                    </li>
                    <li class="nav-item dropdown dropdown-hover mx-2">
                        <a class="text-nowrap" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ Auth::user()->avatar === 'default.png' ? asset('assets/img/utils/default.png') : asset('avatars/'.Auth::user()->avatar) }}" class="avatar avatar-sm">
                            <small class="text-nowrap">{{Auth::user()->email}}</small>
                        </a>
                        <div class="dropdown-menu ms-n3 dropdown-menu-animation dropdown-md p-3 border-radius-lg mt-0 mt-lg-3" aria-labelledby="dropdownMenuPages5">
                            <div class="d-none d-lg-block">
                                <a href="{{ route('producer_profile_view') }}" class="dropdown-item border-radius-md">
                                    <span>{{__('messages.header.user.update_profile')}}</span>
                                </a>
                                <a href="{{ route('producer_farmers_view') }}" class="dropdown-item border-radius-md">
                                    <span>{{__('messages.header.user.my_farmers')}}</span>
                                </a>
                                <a href="{{ route('producer_detail_view', ['producer_id' => Auth::user()->id]) }}" class="dropdown-item border-radius-md">
                                    <span>{{__('messages.header.user.detail')}}</span>
                                </a>
                                <a href="{{ route('logout') }}" class="dropdown-item border-radius-md">
                                    <span>{{__('messages.header.user.logout')}}</span>
                                </a>
                            </div>
                            <div class="d-lg-none">
                                <a href="{{ route('producer_profile_view') }}" class="dropdown-item border-radius-md">
                                    <span>{{__('messages.header.user.update_profile')}}</span>
                                </a>
                                <a href="{{ route('producer_farmers_view') }}" class="dropdown-item border-radius-md">
                                    <span>{{__('messages.header.user.my_farmers')}}</span>
                                </a>
                                <a href="{{ route('producer_detail_view', ['producer_id' => Auth::user()->id]) }}" class="dropdown-item border-radius-md">
                                    <span>{{__('messages.header.user.detail')}}</span>
                                </a>
                                <a href="{{ route('logout') }}" class="dropdown-item border-radius-md">
                                    <span>{{__('messages.header.user.logout')}}</span>
                                </a>
                            </div>
                        </div>
                    </li>
                </ul>
            @else
                <ul class="navbar-nav navbar-nav-hover ms-auto">
                    <li class="nav-item ms-lg-auto">
                        <a class="nav-link nav-link-icon me-2 text-nowrap" href="{{ route('matters_view') }}">
                            <i class="fa fa-navicon me-1"></i>
                            <span class="text-nowrap">{{__('messages.header.matters_view')}}</span>
                        </a>
                    </li>
                    <li class="nav-item ms-lg-auto">
                        <a class="nav-link nav-link-icon me-2 text-nowrap" href="{{ route('applications_view') }}">
                            <i class="fa fa-search me-1"></i>
                            <span class="text-nowrap">{{__('messages.header.applicant_status')}}</span>
                        </a>
                    </li>
                    <li class="nav-item ms-lg-auto">
                        <a class="nav-link nav-link-icon me-2 text-nowrap" href="{{ route('favourite_recruitments_view') }}">
                            <i class="fa fa-star me-1"></i>
                            <span class="text-nowrap">{{__('messages.header.favourite_recruitment')}}</span>
                        </a>
                    </li>
                    <li class="nav-item dropdown dropdown-hover mx-2">
                        <a class="nav-link ps-2 d-flex justify-content-start cursor-pointer align-items-center text-nowrap" id="dropdownMenuPages5" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-comments me-1"></i>
                            <span class="text-nowrap">{{__('messages.header.chatting.title')}}</span>
                            <img src="{{ asset('assets/img/down-arrow-dark.svg') }}" alt="down-arrow" class="arrow ms-auto ms-md-2">
                        </a>
                        <div class="dropdown-menu ms-n3 dropdown-menu-animation dropdown-md p-3 border-radius-lg mt-0 mt-lg-3" aria-labelledby="dropdownMenuPages5">
                            <div class="d-none d-lg-block">
                                <a href="{{ route('chat_recruitments_view') }}" class="dropdown-item border-radius-md">
                                    <span>{{__('messages.header.chatting.favourites')}}</span>
                                </a>
                                <a href="{{ route('favourites_chat') }}" class="dropdown-item border-radius-md">
                                    <span>{{__('messages.header.chatting.free')}}</span>
                                </a>
                            </div>
                            <div class="d-lg-none">
                                <a href="{{ route('chat_recruitments_view') }}" class="dropdown-item border-radius-md">
                                    <span>{{__('messages.header.chatting.favourites')}}</span>
                                </a>
                                <a href="{{ route('favourites_chat') }}" class="dropdown-item border-radius-md">
                                    <span>{{__('messages.header.chatting.free')}}</span>
                                </a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown dropdown-hover mx-2" id="worker_notification_item">
                        <a class="nav-link ps-2 d-flex justify-content-start cursor-pointer align-items-center position-relative" data-bs-toggle="dropdown" aria-expanded="false">
                            <h6><i class="fa fa-bell"></i></h6>
                            <span class="position-absolute top-5 start-0 translate-middle badge rounded-pill bg-danger border border-white small py-1 px-2" style="display: none">
                                <span class="small" id="worker_notification_count">0</span>
                            </span>
                        </a>
                        <div class="dropdown-menu ms-n3 dropdown-menu-animation dropdown-md p-3 border-radius-lg mt-0 mt-lg-3 overflow-auto overflow-x-hidden max-height-250" aria-labelledby="notificationMenu" id="worker_notification_body" style="">
                            <a class="dropdown-item border-radius-md empty">
                                <div class="text-center">
                                    <i class="fa fa-exclamation-triangle" style="font-size: 40px"></i>
                                    <p class="text-center text-bold m-0"> {{__('messages.header.no_news')}} </p>
                                </div>
                            </a>
                            <a class="dropdown-item border-radius-md read-more" href="{{route('news_view')}}">
                                <p class="text-center m-0">
                                    <i class="fa fa-arrow-right"></i>
                                    {{__('messages.action.read_more')}}
                                </p>
                            </a>
                        </div>
                    </li>
                    <li class="nav-item dropdown dropdown-hover mx-2" id="worker_message_item">
                        <a class="nav-link ps-2 d-flex justify-content-start cursor-pointer align-items-center position-relative" data-bs-toggle="dropdown" aria-expanded="false">
                            <h6><i class="fa fa-envelope"></i></h6>
                            <span class="position-absolute top-5 start-0 translate-middle badge rounded-pill bg-danger border border-white small py-1 px-2" style="display: none">
                                <span class="small" id="worker_message_count">0</span>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end ms-n3 dropdown-menu-animation dropdown-md p-3 border-radius-lg mt-0 mt-lg-3 overflow-auto overflow-x-hidden max-height-250" aria-labelledby="messageMenu" id="worker_message_body" style="">
                            <a class="dropdown-item border-radius-md empty">
                                <div class="text-center">
                                    <i class="fa fa-exclamation-triangle" style="font-size: 40px"></i>
                                    <p class="text-center text-bold m-0"> {{__('messages.header.no_msg')}} </p>
                                </div>
                            </a>
                            <a class="dropdown-item border-radius-md read-more" href="{{route('messages_view')}}">
                                <p class="text-center m-0">
                                    <i class="fa fa-arrow-right"></i>
                                    {{__('messages.action.read_more')}}
                                </p>
                            </a>
                        </div>
                    </li>
                    <li class="nav-item dropdown dropdown-hover mx-2">
                        <a class="text-nowrap" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ Auth::user()->avatar === 'default.png' ? asset('assets/img/utils/default.png') : asset('avatars/'.Auth::user()->avatar) }}" class="avatar avatar-sm">
                            <small class="text-nowrap">{{Auth::user()->email}}</small>
                        </a>
                        <div class="dropdown-menu ms-n3 dropdown-menu-animation dropdown-md p-3 border-radius-lg mt-0 mt-lg-3" aria-labelledby="dropdownMenuPages5">
                            <div class="d-none d-lg-block">
                                <a href="{{ route('worker_profile_view') }}" class="dropdown-item border-radius-md">
                                    <span>{{__('messages.header.user.update_profile')}}</span>
                                </a>
                                <a href="{{ route('worker_farms_view') }}" class="dropdown-item border-radius-md">
                                    <span>{{__('messages.header.user.my_farms')}}</span>
                                </a>
                                <a href="{{ route('worker_detail_view') }}" class="dropdown-item border-radius-md">
                                    <span>{{__('messages.header.user.detail')}}</span>
                                </a>
                                <a href="{{ route('logout') }}" class="dropdown-item border-radius-md">
                                    <span>{{__('messages.header.user.logout')}}</span>
                                </a>
                            </div>
                            <div class="d-lg-none">
                                <a href="{{ route('worker_profile_view') }}" class="dropdown-item border-radius-md">
                                    <span>{{__('messages.header.user.update_profile')}}</span>
                                </a>
                                <a href="{{ route('worker_farms_view') }}" class="dropdown-item border-radius-md">
                                    <span>{{__('messages.header.user.my_farms')}}</span>
                                </a>
                                <a href="{{ route('worker_detail_view') }}" class="dropdown-item border-radius-md">
                                    <span>{{__('messages.header.user.detail')}}</span>
                                </a>
                                <a href="{{ route('logout') }}" class="dropdown-item border-radius-md">
                                    <span>{{__('messages.header.user.logout')}}</span>
                                </a>
                            </div>
                        </div>
                    </li>
                </ul>
            @endif
        </div>
    </div>
</nav>

<script src="{{asset('assets/js/core/jquery-3.6.0.min.js')}}" type="text/javascript"></script>
<script src="https://js.pusher.com/7.0.3/pusher.min.js"></script>
<script>
    var pusher = new Pusher("{{env('PUSHER_APP_KEY')}}", {
        cluster: "ap3",
    });

    var chat = pusher.subscribe("chat");

    chat.bind("news-{{Auth::user()->id}}", (data) => {
        alert();
        var news_count = parseInt($("#{{Auth::user()->role}}_notification_count").text());
        $("#{{Auth::user()->role}}_notification_count").text(news_count + 1);
        $("#{{Auth::user()->role}}_notification_body").find('.dropdown-item.empty').remove();
        $("#{{Auth::user()->role}}_notification_body").prepend('<a class="dropdown-item text-warning" href="'+data['link']+'" id="'+data['id']+'" onclick="read_news(this)">'+data['message']+'</a>')
        $("#{{Auth::user()->role}}_notification_item").find('.badge').removeAttr("style");
    });

    chat.bind("receive-{{Auth::user()->id}}", (data) => {
        if (window.location.href.indexOf("chat/recruitment/"+data['recruitment_id']) === -1 && !(window.location.href.indexOf("chat/favourites") !== -1 && data['recruitment_id'] == "0")) {
            if(parseInt(data['recruitment_id']) !== 0) {
                var link = "/dashboard/chat/recruitment/"+data['recruitment_id']+'/'+data['sender_id'];
            }
            else {
                var link = "/dashboard/chat/favourites/"+data['sender_id'];
            }
            var msg_count = parseInt($("#{{Auth::user()->role}}_message_count").text());
            $("#{{Auth::user()->role}}_message_count").text(msg_count + 1);
            $("#{{Auth::user()->role}}_message_body").find('.dropdown-item.empty').remove();
            $("#{{Auth::user()->role}}_message_body").prepend('<a class="dropdown-item text-warning" href="'+link+'" id="'+data['id']+'" onclick="read_msg(this)">'+data['sender']['family_name']+"{{__('messages.alert.new_message_arrived')}}"+'</a>')
            $("#{{Auth::user()->role}}_message_item").find('.badge').removeAttr("style");
        }
    });

    chat.bind("read-message", message_id => {
        $("#{{Auth::user()->role}}_message_body").find("a#"+message_id).find('i:last').text('done_all');
    })

    $(document).ready(function () {
        get_news();
        get_msg();
    })

    function get_news() {
        $.ajax({
            url: "{{ route('get_news') }}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'GET',
            dataType: 'json',
            success: function (res) {
                if(res.length) {
                    $("#{{Auth::user()->role}}_notification_count").text(res.filter(item => item.read === 0).length);
                    $("#{{Auth::user()->role}}_notification_body").find('.dropdown-item:not(.read-more)').remove();
                    $("#{{Auth::user()->role}}_notification_body").prepend(
                        res.map(notification => {
                            var className = notification.read === 0 ? "dropdown-item text-warning" : "dropdown-item";
                            return '<a class="'+className+'" href="'+notification['link']+'" id="'+notification['id']+'" onclick="read_news(this)">'+notification['message']+'</a>'
                        })
                    );
                    if(res.filter(item => item.read === 0).length) $("#{{Auth::user()->role}}_notification_item").find('.badge').removeAttr("style");
                }
            },
            error: function (res) {
                console.log(res);
            },
        });
    }

    function get_msg() {
        $.ajax({
            url: "{{ route('get_msg') }}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'GET',
            dataType: 'json',
            success: function (res) {
                if(res.length) {
                    $("#{{Auth::user()->role}}_message_count").text(res.length);
                    $("#{{Auth::user()->role}}_message_body").find('.dropdown-item:not(.read-more)').remove();
                    $("#{{Auth::user()->role}}_message_body").prepend(
                        res.map(message => {
                            let link;
                            if(message['recruitment_id'] !== 0) {
                                link = "/dashboard/chat/recruitment/"+message['recruitment_id']+'/'+message['sender_id'];
                            }
                            else {
                                link = "/dashboard/chat/favourites/"+message['sender_id'];
                            }
                            return '<a class="dropdown-item" href="'+link+'" id="'+message['id']+'" onclick="read_msg(this)">'+message['sender']['family_name']+"{{__('messages.alert.new_message_arrived')}}"+'</a>'
                        })
                    )
                    $("#{{Auth::user()->role}}_message_item").find('.badge').removeAttr("style");
                }
            },
            error: function (res) {
                console.log(res);
            },
        });
    }

    function read_all_news() {
        swal({
            title: "{{__('messages.alert.confirm') }}",
            text: "{{__('messages.news.read_all_news') }}",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: "{{__('messages.action.yes') }}",
            cancelButtonText: "{{__('messages.action.no') }}",
        }, function () {
            $.ajax({
                url: "{{ route('read_all_news') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                success: function (res) {
                    if(res) {
                        $("#{{Auth::user()->role}}_notification_count").text(0);
                        $("#{{Auth::user()->role}}_notification_body").empty();
                        $("#{{Auth::user()->role}}_notification_item").find('.badge').css("display", "none");
                    }
                },
                error: function (res) {
                    console.log(res);
                },
            });
        });
    }

    function read_all_msg() {
        swal({
            title: "{{__('messages.alert.confirm') }}",
            text: "{{__('messages.chatting.read_all_message') }}",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: "{{__('messages.action.yes') }}",
            cancelButtonText: "{{__('messages.action.no') }}",
        }, function () {
            $.ajax({
                url: "{{ route('read_all_msg') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                success: function (res) {
                    if(res) {
                        $("#{{Auth::user()->role}}_message_count").text(0);
                        $("#{{Auth::user()->role}}_message_body").empty();
                        $("#{{Auth::user()->role}}_message_item").find('.badge').css("display", "none");
                    }
                },
                error: function (res) {
                    console.log(res);
                },
            });
        });
    }

    function read_news(element) {
        $.ajax({
            url: "{{ route('read_news') }}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                id: element.id
            },
            type: 'POST',
        });
    }

    function read_msg(element) {
        $.ajax({
            url: "{{ route('read_msg') }}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                id: element.id
            },
            type: 'POST',
        });
    }
</script>
