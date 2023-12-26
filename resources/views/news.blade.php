@extends('layouts.dashboard')

@section('links')
    @include('css.links')
    <link href='{{ asset('assets/plugins/calendar/main.css') }}' rel='stylesheet' />
@endsection

@section('content')
    <div class="mb-4">
        <h3>{{__('messages.title.news')}}</h3>
    </div>
    <div class="row">
        <div class="text-end">
            <button class="btn btn-danger" onclick="delete_all()">
                <i class="fa fa-trash"></i>
                {{__('messages.action.delete')}}
            </button>
        </div>
        @unless(count($news))
            <div class="card text-center mx-5">
                <i class="fa fa-exclamation-triangle" style="font-size: 60px"></i>
                <p class="text-center text-bold m-3"> {{__('messages.title.no_news')}} </p>
            </div>
        @endunless
        @if(count($news))
            <div class="table-responsive">
                <table class="table align-items-center mb-0">
                    <thead>
                    <tr>
                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                            {{__('messages.news.message')}}
                        </th>
                        <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">{{__('messages.news.date')}}</th>
                        <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">{{__('messages.news.status')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($news as $item)
                        <tr>
                            <td>
                                <a href="{{ $item['link'] }}" class="{{$item['read']?'text-black':'text-warning'}}">
                                    {{ $item['message'] }}
                                </a>
                            </td>
                            <td class="align-middle text-center">
                                <span class="text-secondary text-xs font-weight-bold">{{ $item['created_at'] }}</span>
                            </td>
                            <td class="align-middle text-center">
                                @if($item['read'])
                                    <i class="fa fa-check text-success"></i>
                                @else
                                    <i class="fa fa-close text-warning"></i>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $news->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    @include('scripts.scripts')
    <script>
        function delete_all() {
            swal({
                title: "{{__('messages.alert.confirm')}}",
                text: "{{__('messages.alert.are_you_sure_to_delete_all_news')}}",
                showCancelButton: true,
                closeOnConfirm: false,
                confirmButtonText: "{{__('messages.action.yes')}}",
                cancelButtonText: "{{__('messages.action.no')}}",
            }, function(){
                $.ajax({
                    url: "{{route('clear_all_news')}}",
                    type: 'delete',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function() {
                        location.href = location.href;
                    }
                })
            });
        }
    </script>
@endsection
