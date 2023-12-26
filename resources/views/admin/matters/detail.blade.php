@extends('layouts.adminDashboard')

@section('links')
    @include('css.adminLinks')
@endsection

@section('content')
    <section class="content-header">
        <h1>{{__('messages.sidebar.matter_detail')}}</h1>
    </section>

    <section class="content">
        <div class="row my-4 mb-0 mx-2">
            <div class="col-lg-4 col-md-4 col-sm-12 text-center">
                <div class="card shadow-lg mt-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <a class="d-block blur-shadow-image">
                            <img class="img-rounded" src="{{ !empty($matter['image']) ? asset('uploads/recruitments/'.$matter['image']) : asset('assets/img/utils/empty.png') }}" style="width: 100%">
                        </a>
                    </div>
                    <div class="card-body">
                        <h4>{{ $matter['title'] }}</h4>
                        <p class="text-bold">
                            {{ $matter['producer']['family_name'] }}
                        </p>
                        <input value="{{ $matter['producer']['review'] }}" type="text" class="rating" readonly data-size="sm">
                        <div class="text-center mt-3">
                            @if($matter['status'] == 'draft')
                                <span class="badge" style="background: #b61889">{{__('messages.recruitment.status.draft')}}</span>
                            @elseif($matter['status'] == 'collecting')
                                <span class="badge" style="background: #02bef5">{{__('messages.recruitment.status.collecting')}}</span>
                            @elseif($matter['status'] == 'working')
                                <span class="badge" style="background: #172a89">{{__('messages.recruitment.status.working')}}</span>
                            @elseif($matter['status'] == 'completed')
                                <span class="badge" style="background: #0b5306">{{__('messages.recruitment.status.completed')}}</span>
                            @elseif($matter['status'] == 'canceled')
                                <span class="badge" style="background: #fa0428">{{__('messages.recruitment.status.canceled')}}</span>
                                <p>
                                    {{$matter['comment']}}
                                </p>
                            @elseif($matter['status'] == 'deleted')
                                <span class="badge" style="background: #c19f04">{{__('messages.recruitment.status.deleted')}}</span>
                                <p>
                                    {{$matter['comment']}}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-12">
                <div class="row">
                    <div class="col-12 p-1">
                        <h6>{{__('messages.recruitment.description')}}</h6>
                        <p class="m-0"> {{ $matter['description'] }} </p>
                    </div>
                    <div class="col-12 p-1">
                        <h6>{{ __('messages.recruitment.notice') }}</h6>
                        <p class="m-0"> {!! nl2br($matter['notice']) !!} </p>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="p-3 text-center">
                            <i class="fa fa-map text-primary" style="font-size: 35pt"></i>
                            <h5 class="mt-3">{{ __('messages.recruitment.workplace') }}</h5>
                            <p>{{ $matter['workplace'] }}</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="p-3 text-center">
                            <i class="fa fa-calendar text-primary" style="font-size: 35pt"></i>

                            <h5 class="mt-3">{{ __('messages.recruitment.work_date') }}</h5>
                            <p>
                                {{ format_date($matter['work_date_start']) }}
                                ({{ format_day($matter['work_date_start'], 'short') }})
                                @if($matter['work_date_start'] != $matter['work_date_end'])
                                    ~ {{ format_date($matter['work_date_end']) }}
                                    ({{ format_day($matter['work_date_end'], 'short') }})
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="p-3 text-center">
                            <i class="fa fa-clock text-primary" style="font-size: 35pt"></i>
                            <h5 class="mt-3">{{ __('messages.recruitment.work_time') }}</h5>
                            <p>{{ format_time($matter['work_time_start']) }} ~ {{ format_time($matter['work_time_end']) }}</p>
                        </div>
                    </div>
                </div>
                <div class="row px-3">
                    <div class="col-lg-3 col-md-6 col-sm-12 my-3">
                        <h6>{{ __('messages.recruitment.pay_mode.title') }}</h6>
                        <p class="m-0">
                            {{__('messages.recruitment.pay_mode.'.$matter['pay_mode'])}}
                        </p>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 my-3">
                        <h6>{{ __('messages.recruitment.reward.title') }}</h6>
                        <p class="m-0">
                            {{ $matter['reward_type'] }}({{ $matter['reward_cost'] }}円)
                        </p>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 my-3">
                        <h6>{{ __('messages.recruitment.worker_amount') }}</h6>
                        <p class="m-0">
                            {{ $matter['worker_amount'] }}名
                        </p>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 my-3">
                        <h6>{{ __('messages.recruitment.traffic.title') }}</h6>
                        <p class="m-0">
                            {{ $matter['traffic_type'] == 'include' ? __('messages.recruitment.traffic.include') : $matter['traffic_cost'].'円'}}
                        </p>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 my-3">
                        <h6>{{ __('messages.recruitment.lunch_mode.title') }}</h6>
                        <p class="m-0">
                            {{ $matter['lunch_mode'] ? __('messages.recruitment.lunch_mode.yes') : __('messages.recruitment.lunch_mode.no') }}
                        </p>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 my-3">
                        <h6>{{ __('messages.recruitment.toilet.title') }}</h6>
                        <p class="m-0">
                            {{$matter['toilet']?__('messages.recruitment.toilet.yes'):__('messages.recruitment.toilet.no')}}
                        </p>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 my-3">
                        <h6>{{__('messages.recruitment.insurance.title')}}</h6>
                        <p class="m-0">
                            {{$matter['insurance']?__('messages.recruitment.insurance.yes'):__('messages.recruitment.insurance.no')}}
                        </p>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 my-3">
                        <h6>{{__('messages.recruitment.park.title')}}</h6>
                        <p class="m-0">
                            {{$matter['park']?__('messages.recruitment.park.yes'):__('messages.recruitment.park.no')}}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div style="text-align: right; margin-right: 30px">
            <button class="btn btn-primary mb-3" onclick="javascript:history.go(-1);">
                <i class="fa fa-arrow-left"></i>
                {{__('messages.action.back')}}
            </button>
        </div>
    </section>
@endsection

@section('scripts')
    @include('scripts.adminScripts')
@endsection
