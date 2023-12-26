@extends('layouts.dashboard')

@section('links')
    @include('css.links')
    <link href='{{ asset('assets/plugins/calendar/main.css') }}' rel='stylesheet' />
@endsection

@section('content')
    <div class="mb-4">
        <h3>{{__('messages.dashboard.title')}}</h3>
    </div>
    <div class="row">
        <div class="col-lg-8 col-md-12">
            @if(Auth::user()->role == 'producer')
                <div class="m-3">
                    <span class="badge" style="background: #b61889">{{__('messages.recruitment.status.draft')}}</span>
                    <span class="badge" style="background: #02bef5">{{__('messages.recruitment.status.collecting')}}</span>
                    <span class="badge" style="background: #172a89">{{__('messages.recruitment.status.working')}}</span>
                    <span class="badge" style="background: #0b5306">{{__('messages.recruitment.status.completed')}}</span>
                    <span class="badge" style="background: #fa0428">{{__('messages.recruitment.status.canceled')}}</span>
                    <span class="badge" style="background: #c19f04">{{__('messages.recruitment.status.deleted')}}</span>
                </div>
            @else
                <div class="m-3">
                    <span class="badge" style="background: #8058ef">{{__('messages.applicants.status.waiting')}}</span>
                    <span class="badge" style="background: #069367">{{__('messages.applicants.status.approved')}}</span>
                    <span class="badge" style="background: #fd8503">{{__('messages.applicants.status.rejected')}}</span>
                    <span class="badge" style="background: #ac3d11">{{__('messages.applicants.status.abandoned')}}</span>
{{--                    <span class="badge" style="background: #90ac11">{{__('messages.applicants.status.fired')}}</span>--}}
                    <span class="badge" style="background: #219ff3">{{__('messages.applicants.status.finished')}}</span>
                    <span class="badge" style="background: #fa0428">{{__('messages.recruitment.status.canceled')}}</span>
                    <span class="badge" style="background: #c19f04">{{__('messages.recruitment.status.deleted')}}</span>
                </div>
            @endif
            <div id='calendar'></div>
        </div>
        <div class="col-lg-4 col-md-12 col-sm-12">
            <div class="card mt-7">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-success shadow-primary border-radius-lg py-1 pe-1">
                        <h4 class="text-white font-weight-bolder text-center my-2">
                            {{__('messages.title.news')}}
                        </h4>
                    </div>
                </div>
                <div class="card-body" id="news_body">
                    @unless(count($news) > 0)
                        <p class="text-center m-3" id="empty_title"> {{__('messages.title.no_news')}} </p>
                    @endunless
                    @foreach($news as $news_item)
                        <div class="p-3 info-horizontal">
                            <div class="icon icon-shape  bg-success shadow-primary text-center">
                                @if($news_item['type'] == 'message')
                                    <i class="fa fa-envelope opacity-10"></i>
                                @else
                                    <i class="fa fa-bell opacity-10"></i>
                                @endif
                            </div>
                            <div class="description ps-3">
                                <a href="{{ $news_item['link'] }}" class="mb-0" onclick="read_news({{ $news_item['id'] }})">
                                    {{ $news_item['message'] }}
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @include('scripts.scripts')
    <script src='{{ asset('assets/plugins/calendar/main.js') }}'></script>
    <script src='{{ asset('assets/plugins/calendar/locales-all.js') }}'></script>
    <script>

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth'
                },
                locale: 'ja',
                buttonIcons: false, // show the prev/next text
                navLinks: true, // can click day/week names to navigate views
                editable: false,
                dayMaxEvents: false, // allow "more" link when too many events
                dayMaxEventRows: 3,
                events: @json($events),
            });

            calendar.render();
        });

        var pusher = new Pusher("{{env('PUSHER_APP_KEY')}}", {
            cluster: "ap3",
        });

        var chat = pusher.subscribe("chat");

        chat.bind("news-{{Auth::user()->id}}", (data) => {
            if($("#news_body").find('#empty_title').length) $("#news_body").find('#empty_title').remove();
            $("#news_body").append('\
                <div class="p-3 info-horizontal">\
                    <div class="icon icon-shape  bg-success shadow-primary text-center">\
                        <i class="fa fa-'+(data['type']==='message'?'envelope':'bell')+' opacity-10"></i>\
                    </div>\
                    <div class="description ps-3">\
                        <a href="'+data['link']+'" class="mb-0" onclick="read_news('+data['id']+')">\
                            '+data['message']+'\
                        </a>\
                    </div>\
                </div>'
            );
        });

        function read_news(id) {
            $.ajax({
                url: "{{ route('read_news') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: id
                },
                type: 'POST',
            });
        }

    </script>
@endsection
