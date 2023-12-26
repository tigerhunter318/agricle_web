@extends('layouts.dashboard')

@section('links')
    @include('css.links')
@endsection

@section('content')

    <div class="mb-4">
        <h3>{{__('messages.title.recruitment_collecting')}}</h3>
    </div>

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
                    <div class="mt-3">
                        <span class="text-nowrap">
                            <span class="text-sm text-info">
                                {{__('messages.recruitment.amount.applied')}}:
                            </span>
                            <span class="text-bold text-info">
                                {{ count($recruitment['applicants']) }}
                            </span>
                        </span>
                        ,&nbsp;
                        <span class="text-nowrap">
                            <span class="text-sm text-success">
                                {{__('messages.recruitment.amount.approved')}}:
                            </span>
                            <span class="text-bold text-success">
                                {{ $recruitment['approved_amount'] }}
                            </span>
                        </span>
                        ,&nbsp;
                        <span class="text-nowrap">
                            <span class="text-sm text-danger">
                                {{__('messages.recruitment.amount.plan')}}:
                            </span>
                            <span class="text-bold text-danger">
                                {{ $recruitment['worker_amount'] }}
                            </span>
                        </span>
                    </div>
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
                    <div class="mt-2 text-end">
                        <form
                            class="d-inline"
                            action="{{ route('set_recruitment_status', ['id' => $recruitment['id'], 'status' => 'working']) }}"
                            method="POST"
                            id="completeForm"
                            data-bs-toggle="tooltip"
                            data-bs-placement="bottom"
                            title="{{ $recruitment['approved_amount'] ? __('messages.recruitment.stop_recruit_tooltip') : __('messages.recruitment.cannot_stop_recruit_tooltip') }}"
                            data-container="body"
                            data-animation="true"
                        >
                            @csrf
                            @method('PUT')
                            <button
                                type="button"
                                class="btn btn-success"
                                onclick="finish_recruit()"
                                @if($recruitment['approved_amount']==0)
                                disabled
                                @endif
                            >
                                <i class="fa fa-check"></i>
                                {{__('messages.action.complete_collecting')}}
                            </button>
                        </form>
                        <form id="cancelForm" class="d-inline" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="comment" id="comment" />
                            <button
                                type="button"
                                class="btn btn-danger mx-1"
                                onclick="cancel_recruit('{{ route('set_recruitment_status', ['id' => $recruitment->id, 'status' => 'deleted']) }}')"
                            >
                                <i class="fa fa-close"></i> {{__('messages.action.cancel')}}
                            </button>
                        </form>
                        <a
                            href="{{ route('recruitment_applicants_view', ['recruitment_id' => $recruitment['id']]) }}"
                            type="button"
                            class="btn btn-primary"
                            data-bs-toggle="tooltip"
                            data-bs-placement="bottom"
                            title="{{ __('messages.recruitment.applicants_detail_tooltip') }}"
                            data-container="body"
                            data-animation="true"
                        >
                            <i class="fa fa-arrow-right"></i>
                            {{ __('messages.action.detail') }}
                        </a>
                        <a
                            href="{{ route('clone_view', $recruitment->id) }}"
                            type="button"
                            class="btn btn-outline-info"
                        >
                            <i class="fa fa-clone"></i>
                            {{ __('messages.action.copy') }}
                        </a>
                        <a
                            href="{{ route('recruitment_addon_view', ['recruitment_id' => $recruitment['id']]) }}"
                            type="button"
                            class="btn btn-info letter-spacing-5"
                            data-bs-toggle="tooltip"
                            data-bs-placement="bottom"
                            title="{{ __('messages.recruitment.addon_detail_tooltip') }}"
                            data-container="body"
                            data-animation="true"
                        >
                            <i class="fa fa-plus"></i>
                            {{__('messages.action.add_on')}}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{ $recruitments->links('vendor.pagination.custom') }}

@endsection

@section('scripts')
    @include('scripts.scripts')
    <script>
        function finish_recruit() {
            swal({
                title: "{{__('messages.alert.confirm')}}",
                text: "{{__('messages.alert.are_you_sure_to_finish_collect')}}",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: "{{__('messages.action.yes')}}",
                cancelButtonText: "{{__('messages.action.no')}}",
            }, function () {
                $("#completeForm").submit();
            });
        }
        function cancel_recruit(url) {
            swal({
                title: "{{__('messages.alert.are_you_sure_to_cancel_recruit')}}",
                text: "<textarea type='text' id='comment_text' rows='5' style='border: 1px solid grey; border-radius: 5px; width: 100%; padding: 5px' placeholder='{{__('messages.alert.type_comment')}}'></textarea>",
                html: true,
                showCancelButton: true,
                closeOnConfirm: false,
                confirmButtonText: "{{__('messages.action.yes')}}",
                cancelButtonText: "{{__('messages.action.no')}}",
            }, function(){
                var comment = $("#comment_text").val();
                $("#comment").val(comment);
                $('#cancelForm').attr('action', url).submit();
            });
        }
    </script>
@endsection
