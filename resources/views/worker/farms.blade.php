@extends('layouts.dashboard')

@section('links')
    @include('css.links')
@endsection

@section('content')
    <div class="mb-4">
        <h3>{{__('messages.title.my_farms')}}</h3>
    </div>

    @unless(count($farms))
        <div class="card text-center m-5">
            <i class="fa fa-exclamation-triangle" style="font-size: 60px"></i>
            <p class="text-center text-bold m-3"> {{__('messages.farms.no_farm')}} </p>
        </div>
    @endunless

    @foreach ($farms as $farm)
        <hr class="horizontal dark mt-1" style="height: 2px;"/>
        <div class="row m-0">
            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4 text-center">
                <img
                    src="{{ $farm['avatar'] === 'default.png' ? asset('assets/img/utils/default.png') : asset('avatars/'.$farm['avatar']) }}"
                    class="avatar avatar-md"
                    data-bs-toggle="tooltip"
                    data-bs-placement="bottom"
                    title="{{ $farm['appeal_point'] }}"
                    data-container="body"
                    data-animation="true"
                >
                <a href="{{ route('producer_detail_view', ['producer_id' => $farm['id']]) }}">
                    <p class="m-0 text-bold text-sm text-primary">
                        {{ $farm['family_name'] }}
                    </p>
                </a>
                <input value="{{ $farm['review'] }}" type="text" class="rating" data-size="xs" readonly>
            </div>
            <div class="col-lg-10 col-md-9 col-sm-8 col-xs-8">
                <div class="row">
                    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                        <p class="text-sm m-0">{{ format_address($farm['post_number'], $farm['prefectures'], $farm['city'], $farm['address']) }}</p>
                        <p class="text-sm m-0">{{ $farm['contact_address'] }}</p>
                    </div>
                    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                        <p class="text-sm m-0">{{ $farm['appeal_point'] }}</p>
                        <p class="text-sm m-0">{{ $farm['product_name'] }}</p>
                        <p class="text-sm m-0">{{ $farm['bio'] }}</p>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <a href="{{ route('favourites_chat', ['sender_id' => $farm['id']]) }}" class="btn btn-sm align-bottom bg-gradient-primary">
                            {{__('messages.action.chatting')}}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <hr class="horizontal dark mt-1" style="height: 2px;"/>
    @endforeach

    {{ $farms->links('vendor.pagination.custom') }}

@endsection

@section('scripts')
    @include('scripts.scripts')
@endsection
