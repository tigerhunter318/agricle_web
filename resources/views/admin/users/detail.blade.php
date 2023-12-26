@extends('layouts.adminDashboard')

@section('links')
    @include('css.adminLinks')
@endsection

@section('content')
    <section class="content-header">
        <h1>{{__('messages.sidebar.user_detail')}}</h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-lg-2 col-md-3 text-center">
                <div class="position-relative">
                    <img src="{{ $user->avatar === 'default.png' ? asset('assets/img/utils/default.png') : asset('avatars/'.$user->avatar) }}" alt="Photo 1" class="img-fluid img-rounded">
                    <div class="ribbon-wrapper ribbon-lg">
                        <div class="ribbon bg-success text-lg">
                            {{ $user->role == 'producer' ? __('messages.role.producer') : __('messages.role.worker') }}
                        </div>
                    </div>
                </div>
                <h2 class="lead"><b>{{ $user->family_name }}</b></h2>
                <p class="text-sm">{{ $user->email }}</p>
            </div>
            <div class="col-lg-10 col-md-9 p-3 pl-5">
                <div class="row">
                    <div class="col-6">
                        <p><b>{{__('messages.profile.name')}}: </b> {{ $user->family_name }} </p>
                        <p><b>{{__('messages.profile.name_read')}}: </b> {{ $user->name }} </p>
                        @if($user->role == 'worker')
                            <p><b>{{__('messages.profile.nickname')}}: </b> {{ $user->nickname }} </p>
                            <p><b>{{__('messages.profile.gender.title')}}: </b> {{ $user->gender ? __('messages.profile.gender.man') : __('messages.profile.gender.woman') }} </p>
                            <p><b>{{__('messages.profile.birthday')}}: </b> {{ format_date($user->birthday) }} </p>
                            <p><b>{{__('messages.profile.address')}}: </b> {{ format_address($user->post_number, $user->prefectures, $user->city, $user->address) }} </p>
                        @endif
                        @if($user->role == 'producer')
                            <p><b>{{__('messages.profile.contact_address')}}: </b> {{ format_address($user->post_number, $user->prefectures, $user->city, $user->contact_address) }} </p>
                            <p><b>{{__('messages.profile.management_mode.title')}}: </b> {{ $user['management_mode'] == 'individual' ? __('messages.profile.management_mode.individual') : __('messages.profile.management_mode.corporation') }} </p>
                        @endif
                    </div>
                    <div class="col-6">
                        @if($user->role == 'worker')
                            <p><b>{{__('messages.profile.emergency_phone')}}: </b> {{ $user->emergency_phone }} </p>
                            <p><b>{{__('messages.profile.emergency_relation')}}: </b> {{ $user->emergency_relation }} </p>
                            <p><b>{{__('messages.profile.job')}}: </b> {{ $user->job }} </p>
                            <p><b>{{__('messages.profile.bio')}}: </b> {{ $user->bio }} </p>
                        @endif
                        @if($user->role == 'producer')
                            <p><b>{{__('messages.profile.agency_name')}}: </b> {{ $user->agency_name }} </p>
                            <p><b>{{__('messages.profile.agency_phone')}}: </b> {{ $user->agency_phone }} </p>
                            <p><b>{{__('messages.profile.insurance.title')}}: </b> {{ $user->insurance ? __('messages.profile.insurance.yes') : __('messages.profile.insurance.no') }} </p>
                        @endif
                        <p><b>{{__('messages.profile.appeal_point')}}: </b> <br/> {{ $user->appeal_point }} </p>
                    </div>
                </div>
            </div>
        </div>
        <div style="text-align: right; margin-right: 30px">
            <button class="btn btn-primary" onclick="javascript:history.go(-1);">
                <i class="fa fa-arrow-left"></i>
                {{__('messages.action.back')}}
            </button>
        </div>
    </section>
@endsection

@section('scripts')
    @include('scripts.adminScripts')
@endsection
