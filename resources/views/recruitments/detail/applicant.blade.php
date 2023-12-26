@extends('layouts.dashboard')

@section('links')
    @include('css.links')
@endsection

@section('content')
    <div class="mb-4">
        <h3>{{__('messages.title.recruitment_applicant')}}</h3>
    </div>

    <x-applicant :applicant="$worker"/>

    <div class="mb-0 w-100">
        <h5>{{__('messages.applicants.apply_memo')}}</h5>
    </div>

    <div class="w-100">
        <p class="text-bold">
            {{ $applicant['apply_memo'] }}
        </p>
    </div>

    <div class="mb-0 w-100">
        <h5>{{__('messages.profile.matching_history')}}</h5>
    </div>

    @unless(count($recruitments))
        <div class="card text-center m-5">
            <i class="fa fa-exclamation-triangle" style="font-size: 60px"></i>
            <p class="text-center text-bold m-3"> {{__('messages.recruitment.no_data')}} </p>
        </div>
    @endunless

    @foreach ($recruitments as $each)
        <div class="card m-2">
            <div class="row my-4 mx-2 mb-2">
                <div class="col-lg-3 col-md-4 col-sm-12 text-center">
                    <img
                        src="{{ !empty($each['image']) ? asset('uploads/recruitments/'.$each['image']) : asset('assets/img/utils/empty.png') }}"
                        class="border-radius-md img-fluid mb-1"
                        style="width: 100%"
                    />
                    <input value="{{ $each['worker_review'] }}" type="text" class="rating" data-size="sm" readonly>
                    <p class="expander">
                        {{ $each['worker_evaluation'] }}
                    </p>
                    @if($each['applicant_status'] == 'waiting')
                        <span class="badge" style="background: #8058ef">{{__('messages.applicants.status.waiting')}}</span>
                    @elseif($each['applicant_status'] == 'approved')
                        <span class="badge" style="background: #069367">{{__('messages.applicants.status.approved')}}</span>
                    @elseif($each['applicant_status'] == 'rejected')
                        <span class="badge" style="background: #fd8503">{{__('messages.applicants.status.rejected')}}</span>
                    @elseif($each['applicant_status'] == 'abandoned')
                        <span class="badge" style="background: #ac3d11">{{__('messages.applicants.status.abandoned')}}</span>
                        {{--                    @elseif($each['applicant_status'] == 'fired')--}}
                        {{--                        <span class="badge" style="background: #90ac11">{{__('messages.applicants.status.fired')}}</span>--}}
                    @elseif($each['applicant_status'] == 'finished')
                        <span class="badge" style="background: #219ff3">{{__('messages.applicants.status.finished')}}</span>
                    @endif
                </div>
                <div class="col-lg-9 col-md-8 col-sm-12">
                    <h5>
                        <span>
                            {{ $each['title'] }}
                        </span>
                        <span>
                            <a href="{{ route('producer_detail_view', $each['producer_id']) }}" class="text-info text-decoration-underline text-bold m-0">
                                ({{ $each['family_name'] }})
                            </a>
                        </span>
                    </h5>
                    <p class="text-dark text-sm m-0">
                        {{ $each['description'] }}
                    </p>
                    <div class="row mt-1">
                        <div class="col-12">
                            <p class="text-sm mb-1">
                                <i
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="bottom"
                                    title="{{ __('messages.recruitment.workplace') }}"
                                    data-container="body"
                                    data-animation="true"
                                    class="fa fa-map-marker text-warning mx-2"></i>
                                {{ format_address($each['post_number'], $each['prefectures'], $each['city'], $each['workplace']) }}
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
                                {{ format_date($each['work_date_start']) }}
                                @if($each['work_date_start'] != $each['work_date_end'])
                                    ~ {{ format_date($each['work_date_end']) }}
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
                                {{ format_time($each['work_time_start']) }} ~ {{ format_time($each['work_time_end']) }}
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
                                {{ $each['reward_type'] }}({{ $each['reward_cost'] }}円) ・{{__('messages.recruitment.pay_mode.'.$each['pay_mode'])}}
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
                                {{ $each['worker_amount'] }}名
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
                                <span class="text-xs">({{ $each['traffic_type'] == 'include' ? __('messages.recruitment.traffic.include') : $each['traffic_cost'].'円'}})</span>
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
                                <span class="text-xs">({{ $each['lunch_mode'] ? __('messages.recruitment.lunch_mode.yes') : __('messages.recruitment.lunch_mode.no') }})</span>
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
                                <span class="text-xs">({{$each['toilet']?__('messages.recruitment.toilet.yes'):__('messages.recruitment.toilet.no')}})</span>
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
                                <span class="text-xs">({{$each['insurance']?__('messages.recruitment.insurance.yes'):__('messages.recruitment.insurance.no')}})</span>
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
                                <span class="text-xs">({{$each['park']?__('messages.recruitment.park.yes'):__('messages.recruitment.park.no')}})</span>
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
                                {{ $each['notice'] }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{ $recruitments->links('vendor.pagination.custom') }}


    <div class="m-3" style="text-align: right">
        @if($applicant['status'] == 'waiting')
            <button type="button" class="btn btn-success m-2" data-bs-toggle="modal" data-bs-target="#commentModal" onclick="show_btn('approved')">
                <i class="fa fa-check"></i> {{__('messages.applicants.status.approved')}}
            </button>
            <button type="button" class="btn btn-primary m-2" data-bs-toggle="modal" data-bs-target="#commentModal" onclick="show_btn('rejected')">
                <i class="fa fa-close"></i> {{__('messages.applicants.status.rejected')}}
            </button>
        @endif
        <div class="modal fade" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="commentLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="commentLabel">{{__('messages.alert.confirm')}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="comment_modal_body">
                        <form enctype='multipart/form-data' method="POST" action="{{ route('set_applicant_status', ['recruitment_id' => $recruitment['recruitment_id'], 'worker_id' => $worker['id']]) }}" id="form" autocomplete="off">
                            @method('PUT')
                            @csrf
                            <input type="hidden" name="status" id="status" value="approved">
                            <div class="input-group input-group-outline">
                                <textarea name="employ_memo" id="employ_memo" class="form-control" type="text" rows="5" placeholder="{{__('messages.applicants.apply_memo')}}"></textarea>
                            </div>
                            <small class="text-danger" id="employ_memo_error" style="display: none"></small>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">キャンセル</button>
                        <button type="button" class="btn btn-success" onclick="employ('approved')" id="approved_btn"> <i class="fa fa-check"></i> {{__('messages.applicants.status.approved')}}  </button>
                        <button type="button" class="btn btn-primary" onclick="employ('rejected')" id="rejected_btn"> <i class="fa fa-close"></i> {{__('messages.applicants.status.rejected')}}  </button>
                    </div>
                </div>
            </div>
        </div>

        <a href="{{ route('recruitment_applicants_view', ['recruitment_id' => $recruitment['recruitment_id']]) }}" class="btn btn-secondary m-2">
            <i class="fa fa-list"></i>
            {{__('messages.action.applicants_list')}}
        </a>
    </div>
@endsection

@section('scripts')
    @include('scripts.scripts')
    <script>
        $('.expander').expander({
            expandText: "{{__('messages.action.read_more')}}",
            userCollapseText: "{{__('messages.action.read_less')}}",
            slicePoint: 50
        });

        function show_btn(status) {
            if(status === 'approved') {
                $('#approved_btn').css('display', 'inline');
                $('#rejected_btn').css('display', 'none');
            }
            else {
                $('#approved_btn').css('display', 'none');
                $('#rejected_btn').css('display', 'inline');
            }
        }

        function employ(status) {
            // if(!!$("#employ_memo").val()) {
            $("#status").val(status)
            $("#form").submit();
            // }
            // else {
            //     swal({
            {{--        title: "{{__('messages.alert.warning')}}",--}}
            {{--        text: "{{__('messages.matters.you_have_to_input_comment')}}",--}}
            //         type: 'warning',
            {{--        confirmButtonText: "{{__('messages.action.yes')}}",--}}
            //     });
            // }
        }
    </script>
@endsection
