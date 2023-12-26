@extends('layouts.dashboard')

@section('links')
    @include('css.links')
@endsection

@section('content')
    <div class="mb-3">
        <h3> {{ __('messages.header.favourite_recruitment') }} </h3>
    </div>

    <div class="row">
        <div class="col-12">
            {{ $recruitments->links('vendor.pagination.custom') }}

            @unless(count($recruitments))
                <div class="card text-center m-5">
                    <i class="fa fa-exclamation-triangle" style="font-size: 60px"></i>
                    <p class="text-center text-bold m-3"> {{__('messages.recruitment.no_data')}} </p>
                </div>
            @endunless

            @foreach ($recruitments as $recruitment)
                <div class="card mb-2">
                    <div class="row my-4 mx-2">
                        <div class="col-lg-3 col-md-4 col-sm-12 text-center">
                            <img
                                src="{{ !empty($recruitment['image']) ? asset('uploads/recruitments/'.$recruitment['image']) : asset('assets/img/utils/empty.png') }}"
                                class="border-radius-md img-fluid"
                                style="width: 100%"
                            />
                        </div>
                        <div class="col-lg-9 col-md-8 col-sm-12">
                            <div class="mb-1">
                                @if($recruitment['applicant_status'] == 'waiting')
                                    <span class="badge" style="background: #8058ef">{{__('messages.applicants.status.waiting')}}</span>
                                @elseif($recruitment['applicant_status'] == 'approved')
                                    <span class="badge" style="background: #069367">{{__('messages.applicants.status.approved')}}</span>
                                @elseif($recruitment['applicant_status'] == 'rejected')
                                    <span class="badge" style="background: #fd8503">{{__('messages.applicants.status.rejected')}}</span>
                                @elseif($recruitment['applicant_status'] == 'abandoned')
                                    <span class="badge" style="background: #ac3d11">{{__('messages.applicants.status.abandoned')}}</span>
                                    {{--                    @elseif($recruitment['applicant_status'] == 'fired')--}}
                                    {{--                        <span class="badge" style="background: #90ac11">{{__('messages.applicants.status.fired')}}</span>--}}
                                @elseif($recruitment['applicant_status'] == 'finished')
                                    <span class="badge" style="background: #219ff3">{{__('messages.applicants.status.finished')}}</span>
                                @endif
                            </div>
                            <h5>
                                {{ $recruitment['title'] }}
                            </h5>
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
                                <div class="col-12">
                                    <span class="text-md text-success text-bold mb-1">
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
                                    </span>
                                    <span class="text-md text-success text-bold mb-1">
                                        <i
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="bottom"
                                            title="{{ __('messages.recruitment.work_time') }}"
                                            data-container="body"
                                            data-animation="true"
                                            class="fa fa-clock text-success mx-2"></i>
                                        {{ format_time($recruitment['work_time_start']) }} ~ {{ format_time($recruitment['work_time_end']) }}
                                    </span>
                                    <span class="text-md text-success text-bold mb-1">
                                        <i
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="bottom"
                                            title="{{ __('messages.recruitment.worker_amount') }}"
                                            data-container="body"
                                            data-animation="true"
                                            class="fa fa-users text-success mx-2"></i>
                                        {{ $recruitment['worker_amount'] }}名
                                    </span>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <p class="text-sm my-1">
                                        <i
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="bottom"
                                            title="{{ __('messages.recruitment.pay_mode.title') }}"
                                            data-container="body"
                                            data-animation="true"
                                            class="fa fa-jpy text-info mx-2"></i>
                                        {{ $recruitment['reward_type'] }}({{ $recruitment['reward_cost'] }}円) ・{{__('messages.recruitment.pay_mode.'.$recruitment['pay_mode'])}}
                                    </p>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
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
                            </div>
                            <div class="m-3">
                                @if(!$recruitment['isApplied'])
                                    <a
                                        href="{{ route('matter_detail_view', $recruitment->id) }}"
                                        type="button"
                                        class="btn btn-primary"
                                        data-bs-toggle="tooltip"
                                        data-bs-placement="bottom"
                                        title="{{ __('messages.matters.matter_title_tooltip') }}"
                                        data-container="body"
                                        data-animation="true"
                                    >
                                        <i class="fa fa-arrow-right text-sm"></i>
                                        {{__('messages.action.detail')}}
                                    </a>
                                @else
                                    <a
                                        href="{{ route('favourite_recruitment_view', $recruitment->id) }}"
                                        type="button"
                                        class="btn btn-primary"
                                    >
                                        <i class="fa fa-arrow-right text-sm"></i>
                                        {{__('messages.action.detail')}}
                                    </a>
                                @endif
                                <a
                                    href="{{ route('producer_detail_view', $recruitment->producer_id) }}"
                                    class="btn btn-info"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="bottom"
                                    title="{{ __('messages.matters.producer_title_tooltip') }}"
                                    data-container="body"
                                    data-animation="true"
                                >
                                    <i class="fa fa-user text-sm"></i>
                                    {{__('messages.role.producer')}}
                                </a>
                                <a
                                    type="button"
                                    id="{{$recruitment['id']}}"
                                    class="btn btn-danger favourite-btn"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="bottom"
                                    title="{{ __('messages.matters.favourite_tooltip') }}"
                                    data-container="body"
                                    data-animation="true"
                                >
                                    <i class="fa fa-trash text-sm"></i>
                                    {{__('messages.action.delete')}}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            {{ $recruitments->links('vendor.pagination.custom') }}
        </div>
    </div>
@endsection

@section('scripts')
    @include('scripts.scripts')
    <script>
        $(".favourite-btn").click(function () {
            $.ajax({
                url: "{{ route('unset_recruitment_favourite') }}",
                type: "PUT",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    recruitment_id: this.id
                },
                success: function (res) {
                    if(res) {
                        $.toast({
                            heading: '{{__('messages.alert.success')}}',
                            text: '{{__('messages.alert.done_success')}}',
                            position: 'top-right',
                            icon: 'success'
                        })
                        location.reload();
                    }
                },
                error: function (res) {
                    $.toast({
                        heading: '{{__('messages.alert.error')}}',
                        text: '{{__('messages.alert.done_error')}}',
                        position: 'top-right',
                        icon: 'error'
                    })
                    console.log(res);
                }
            })
        })
    </script>
@endsection
