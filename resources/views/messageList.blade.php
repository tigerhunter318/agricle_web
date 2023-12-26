@unless(count($messages))
    <div class="text-center m-5">
        <i class="fa fa-inbox" style="font-size: 60px"></i>
        <p class="text-center text-bold m-3"> {{__('messages.messages.no_message')}} </p>
    </div>
@endunless

@if(count($messages))
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
            @foreach($messages as $item)
                <tr>
                    <td class="w-80">
                        <a href="{{ $item['recruitment_id'] != 0 ? '/dashboard/chat/recruitment/'.$item['recruitment_id'].'/'.$item['sender_id'] : '/dashboard/chat/favourites/'.$item['sender_id'] }}" class="text-wrap {{$item['read']?'text-black':'text-warning'}}">
                            {{ mb_strimwidth($item['message'], 0, 150, "...") }}
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
        {{ $messages->links('vendor.pagination.custom') }}
    </div>
@endif
