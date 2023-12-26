@extends('layouts.dashboard')

@section('links')
    @include('css.links')
@endsection

@section('content')
    <div class="mb-4">
        <h3>{{__('messages.title.recruitment_draft')}}</h3>
    </div>
    <a class="btn bg-info text-white" href="{{ route('recruitments.create') }}"> <i class="fa fa-plus"></i> {{__('messages.recruitment.create')}} </a>

    {{ $recruitments->links('vendor.pagination.custom') }}

    @unless(count($recruitments))
        <div class="card text-center m-5">
            <i class="fa fa-exclamation-triangle" style="font-size: 60px"></i>
            <p class="text-center text-bold m-3"> {{__('messages.recruitment.no_data')}} </p>
        </div>
    @endunless

    @foreach ($recruitments as $recruitment)
        <div class="card m-2">
            <div class="row my-4 mx-2 mb-0">
                <div class="col-lg-3 col-md-4 col-sm-12 text-center">
                    <img
                        src="{{ !empty($recruitment['image']) ? asset('uploads/recruitments/'.$recruitment['image']) : asset('assets/img/utils/empty.png') }}"
                        class="border-radius-md img-fluid"
                        style="width: 100%"
                    />
                </div>
                <div class="col-lg-9 col-md-8 col-sm-12">
                    <h5> {{ $recruitment['title'] }} </h5>
                    <p class="text-dark text-sm"> {{ $recruitment['description'] }} </p>
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
                                {{ $recruitment['workplace'] }}
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
                                @if($recruitment['work_date_start'] != $recruitment['work_date_end'])
                                    ~ {{ format_date($recruitment['work_date_end']) }}
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
                                {{ $recruitment['reward_type'] }}({{ $recruitment['reward_cost'] }}円) ・{{__('messages.recruitment.pay_mode.'.$recruitment['pay_mode'])}}
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
                                    class="fas fa-toilet text-info mx-2"></i>
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
                    <div class="mt-2 text-end">
                        <div class="btn-group btn-group-sm" role="group">
                            <a
                                type="button"
                                class="btn btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#contractConfirmModal{{$loop->index}}"
                            >
                                <i class="fa fa-external-link text-sm"></i>
                                {{__('messages.action.publish')}}
                            </a>
                            <a
                                href="{{ route('recruitments.edit', $recruitment->id) }}"
                                class="btn btn-info"
                            >
                                <i class="fa fa-edit text-sm"></i>
                                {{__('messages.action.edit')}}
                            </a>
                            <a
                                href="{{ route('clone_view', ['recruitment_id' => $recruitment->id]) }}"
                                class="btn btn-outline-success"
                            >
                                <i class="fa fa-clone text-sm"></i>
                                {{__('messages.action.copy')}}
                            </a>
                            <a
                                type="button"
                                class="btn btn-warning"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteConfirmModal{{$loop->index}}"
                            >
                                <i class="fa fa-trash text-sm"></i>
                                {{__('messages.action.delete')}}
                            </a>
                        </div>
                        <div class="modal fade" id="contractConfirmModal{{$loop->index}}" tabindex="-1" role="dialog" aria-labelledby="contractModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="contractModalLabel">{{__('messages.alert.collect')}}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        {{__('messages.alert.would_you_publish')}}
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">
                                            <i class="fa fa-close"></i> {{__('messages.action.cancel')}}</button>
                                        <form action="{{ route('set_recruitment_status', ['id' => $recruitment->id, 'status' => 'collecting']) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn bg-gradient-primary">
                                                <i class="fa fa-check"></i> {{__('messages.action.set_collect')}} </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="deleteConfirmModal{{$loop->index}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">{{__('messages.alert.are_you_sure')}}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        {{__('messages.alert.delete_recruitment_message')}}
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">キャンセル</button>
                                        <form action="{{ route('recruitments.destroy', $recruitment->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn bg-gradient-primary">{{__('messages.action.delete')}}</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{ $recruitments->links('vendor.pagination.custom') }}
@endsection

@section('scripts')
    @include('scripts.scripts')
@endsection
