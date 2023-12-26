@extends('layouts.dashboard')

@section('links')
    @include('css.links')
@endsection

@section('content')
    <div class="mb-4">
        <h3>{{__('messages.chatting.recruitment_list')}}</h3>
    </div>

    @unless(count($recruitments))
        <div class="card text-center m-5">
            <i class="fa fa-exclamation-triangle" style="font-size: 60px"></i>
            <p class="text-center text-bold m-3"> {{__('messages.recruitment.no_data')}} </p>
        </div>
    @endunless

    @foreach ($recruitments as $recruitment)
        <div class="card m-2">
            <div class="row my-4 mb-0 mx-2">
                <div class="col-lg-3 col-md-4 col-sm-12 text-center">
                    <img
                        src="{{ !empty($recruitment['image']) ? asset('uploads/recruitments/'.$recruitment['image']) : asset('assets/img/utils/empty.png') }}"
                        class="border-radius-md img-fluid"
                        style="width: 100%"
                    />
                </div>
                <div class="col-lg-9 col-md-8 col-sm-12">
                    <h5>
                        {{ $recruitment['title'] }}
                        @if(Auth::user()->role == 'worker')
                            ({{ $recruitment['family_name'] }})
                        @endif
                    </h5>
                    <p class="text-dark text-sm mb-1"> {{ $recruitment['description'] }} </p>
                    <div class="row">
                        <div class="col-12">
                            <p class="text-sm mb-1">
                                <i
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="bottom"
                                    title="{{ __('messages.recruitment.workplace') }}"
                                    data-container="body"
                                    data-animation="true"
                                    class="fa fa-map-marker text-warning mx-2"></i>
                                {{ format_address($recruitment['post_number'], $recruitment['prefectures'], $recruitment['city'], $recruitment['workplace']) }}
                            </p>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12">
                            <p class="text-sm mb-1">
                                <i
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="bottom"
                                    title="{{ __('messages.recruitment.work_date') }}"
                                    data-container="body"
                                    data-animation="true"
                                    class="fa fa-calendar text-success mx-2"></i>
                                {{ format_date($recruitment['work_date_start']) }}
                                ({{ format_day($recruitment['work_date_start'], 'short') }})
                            @if($recruitment['work_date_start'] != $recruitment['work_date_end'])
                                    ~ {{ format_date($recruitment['work_date_end']) }}
                                    ({{ format_day($recruitment['work_date_end'], 'short') }})
                                @endif
                            </p>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12">
                            <p class="text-sm mb-1">
                                <i
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="bottom"
                                    title="{{ __('messages.recruitment.work_time') }}"
                                    data-container="body"
                                    data-animation="true"
                                    class="fa fa-clock text-success mx-2"></i>
                                {{ format_time($recruitment['work_time_start']) }} ~ {{ format_time($recruitment['work_time_end']) }}
                            </p>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12">
                            <p class="text-sm mb-1">
                                <i
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="bottom"
                                    title="{{ __('messages.recruitment.pay_mode.title') }}"
                                    data-container="body"
                                    data-animation="true"
                                    class="fa fa-jpy text-success mx-2"></i>
                                {{ $recruitment['reward'] }} ・{{__('messages.recruitment.pay_mode.'.$recruitment['pay_mode'])}}
                            </p>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <p class="text-sm m-0">
                                <i
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="bottom"
                                    title="{{ __('messages.recruitment.worker_amount') }}"
                                    data-container="body"
                                    data-animation="true"
                                    class="fa fa-users text-info mx-2"></i>
                                {{ $recruitment['worker_amount'] }}名
                            </p>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <p class="text-sm m-0">
                            <p class="text-sm my-1">
                                <i
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="bottom"
                                    title="{{ __('messages.recruitment.traffic.title') }}"
                                    data-container="body"
                                    data-animation="true"
                                    class="fa fa-bus text-info mx-2"></i>
                                {{__('messages.recruitment.traffic.title')}}
                                <span class="text-xs">({{ $recruitment['traffic_type'] == 'include' ? __('messages.recruitment.traffic.include') : $recruitment['traffic_cost'].'円'}})</span>
                            </p>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <p class="text-sm m-0">
                                <i
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="bottom"
                                    title="{{ __('messages.recruitment.lunch_mode.title') }}"
                                    data-container="body"
                                    data-animation="true"
                                    class="fa fa-cutlery text-info mx-2"></i>
                                {{__('messages.recruitment.lunch_mode.title')}}
                                <span class="text-xs">({{ $recruitment['lunch_mode'] ? __('messages.recruitment.lunch_mode.yes') : __('messages.recruitment.lunch_mode.no') }})</span>
                            </p>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <p class="text-sm m-0">
                                <i
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="bottom"
                                    title="{{ __('messages.recruitment.toilet.title') }}"
                                    data-container="body"
                                    data-animation="true"
                                    class="fa fa-bath text-info mx-2"></i>
                                {{__('messages.recruitment.toilet.title')}}
                                <span class="text-xs">({{$recruitment['toilet']?__('messages.recruitment.toilet.yes'):__('messages.recruitment.toilet.no')}})</span>
                            </p>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <p class="text-sm m-0">
                                <i
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="bottom"
                                    title="{{ __('messages.recruitment.insurance.title') }}"
                                    data-container="body"
                                    data-animation="true"
                                    class="fa fa-heart text-info mx-2"></i>
                                {{__('messages.recruitment.insurance.title')}}
                                <span class="text-xs">({{$recruitment['insurance']?__('messages.recruitment.insurance.yes'):__('messages.recruitment.insurance.no')}})</span>
                            </p>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <p class="text-sm m-0">
                                <i
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="bottom"
                                    title="{{ __('messages.recruitment.park.title') }}"
                                    data-container="body"
                                    data-animation="true"
                                    class="fa fa-product-hunt text-info mx-2"></i>
                                {{__('messages.recruitment.park.title')}}
                                <span class="text-xs">({{$recruitment['park']?__('messages.recruitment.park.yes'):__('messages.recruitment.park.no')}})</span>
                            </p>
                        </div>
                        <div class="col-12 mt-2">
                            <p class="text-sm">
                                <i
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="bottom"
                                    title="{{ __('messages.recruitment.notice') }}"
                                    data-container="body"
                                    data-animation="true"
                                    class="fa fa-exclamation-triangle text-danger mx-2"></i>
                                {{ $recruitment['notice'] }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mx-2">
                <a
                    href="{{ route('recruitment_chat', ['recruitment_id' => $recruitment['id']]) }}"
                    type="button"
                    class="btn btn-outline-info btn-sm btn-icon"
                    data-bs-toggle="tooltip"
                    data-bs-placement="bottom"
                    title="{{ __('messages.chatting.chat_detail_tooltip') }}"
                    data-container="body"
                    data-animation="true"
                >
                    <i class="material-icons text-sm">chat</i>
                    {{__('messages.action.chatting')}}
                </a>
            </div>
        </div>
    @endforeach

    {{ $recruitments->links('vendor.pagination.custom') }}

@endsection

@section('scripts')
    @include('scripts.scripts')
@endsection
