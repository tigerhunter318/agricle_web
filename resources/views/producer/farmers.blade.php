@extends('layouts.dashboard')

@section('links')
    @include('css.links')
@endsection

@section('content')
    <div class="mb-4">
        <h3>マイfarmerリスト</h3>
    </div>

    @unless(count($farmers))
        <div class="card text-center m-5">
            <i class="fa fa-exclamation-triangle" style="font-size: 60px"></i>
            <p class="text-center text-bold m-3"> {{__('messages.farmers.no_farmer')}} </p>
        </div>
    @endunless

    @foreach ($farmers as $farmer)
        <hr class="horizontal dark mt-1" style="height: 2px;"/>
        <div class="row m-0">
            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4 text-center">
                <img
                    src="{{ $farmer['avatar'] === 'default.png' ? asset('assets/img/utils/default.png') : asset('avatars/'.$farmer['avatar']) }}"
                    class="avatar avatar-sm"
                    data-bs-toggle="tooltip"
                    data-bs-placement="bottom"
                    title="{{ $farmer['appeal_point'] }}"
                    data-container="body"
                    data-animation="true"
                >
                <h6 class="mb-0 text-xs">{{ $farmer['nickname'] }}</h6>
            </div>
            <div class="col-lg-10 col-md-9 col-sm-8 col-xs-8">
                <div class="row">
                    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                        <h6 class="mb-0">
                            {{ $farmer['family_name'] }}
                            <span class="text-sm m-0">({{ $farmer['gender'] == 'man' ? __('messages.profile.gender.man') : __('messages.profile.gender.woman') }})</span>
                        </h6>
                        <p class="text-sm m-0">{{ $farmer['nickname'] }}</p>
                        <p class="text-sm m-0">
                            {{ date_diff(date_create($farmer['birthday']), date_create(date("Y-m-d")))->format('%y') }}歳
                        </p>
                    </div>
                    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                        <p class="text-sm m-0">{{ $farmer['job'] }}</p>
                        <p class="text-sm m-0">{{ $farmer['bio'] }}</p>
                        <input value="{{ $farmer['review'] }}" type="text" class="rating" data-size="xs" readonly>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <a href="{{ route('favourites_chat', ['sender_id' => $farmer['id']]) }}" class="btn btn-sm align-bottom bg-gradient-primary">
                            {{__('messages.action.chatting')}}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <hr class="horizontal dark mt-1" style="height: 2px;"/>
    @endforeach

    {{ $farmers->links('vendor.pagination.custom') }}

@endsection

@section('scripts')
    @include('scripts.scripts')
@endsection
