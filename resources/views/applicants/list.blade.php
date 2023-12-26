{{ $recruitments->links('vendor.pagination.ajax') }}

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
                    <span class="text-bold text-primary">{{__('messages.recruitment.status.title')}}: </span>
                    @if($recruitment['recruitment_status'] == 'draft')
                        <span class="badge" style="background: #b61889">{{__('messages.recruitment.status.draft')}}</span>
                    @elseif($recruitment['recruitment_status'] == 'collecting')
                        <span class="badge" style="background: #02bef5">{{__('messages.recruitment.status.collecting')}}</span>
                    @elseif($recruitment['recruitment_status'] == 'working')
                        <span class="badge" style="background: #172a89">{{__('messages.recruitment.status.working')}}</span>
                    @elseif($recruitment['recruitment_status'] == 'completed')
                        <span class="badge" style="background: #0b5306">{{__('messages.recruitment.status.completed')}}</span>
                    @elseif($recruitment['recruitment_status'] == 'canceled')
                        <span class="badge" style="background: #fa0428">{{__('messages.recruitment.status.canceled')}}</span>
                    @elseif($recruitment['recruitment_status'] == 'deleted')
                        <span class="badge" style="background: #d09010">{{__('messages.recruitment.status.deleted')}}</span>
                    @endif
                </div>
                <h5>
                    <span>
                        {{ $recruitment['title'] }}
                    </span>
                    <span>
                        <a href="{{ route('producer_detail_view', $recruitment['producer_id']) }}" class="text-info text-decoration-underline text-bold m-0">
                            ({{ $recruitment['family_name'] }})
                        </a>
                    </span>
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
                    <div class="mb-1">
                        <span class="text-bold text-primary">{{__('messages.applicants.status.title')}}: </span>
                    @if($recruitment['applicant_status'] == 'waiting')
                            <span class="badge" style="background: #8058ef">{{__('messages.applicants.status.waiting')}}</span>
                        @elseif($recruitment['applicant_status'] == 'approved')
                            <span class="badge" style="background: #069367">{{__('messages.applicants.status.approved')}}</span>
                            <span class="text-xs" style="color: #069367"> - {{ $recruitment['employ_memo'] }}</span>
                        @elseif($recruitment['applicant_status'] == 'rejected')
                            <span class="badge" style="background: #fd8503">{{__('messages.applicants.status.rejected')}}</span>
                            <span class="text-xs" style="color: #fd8503"> - {{ $recruitment['employ_memo'] }}</span>
                        @elseif($recruitment['applicant_status'] == 'abandoned')
                            <span class="badge" style="background: #ac3d11">{{__('messages.applicants.status.abandoned')}}</span>
                            {{--                    @elseif($recruitment['applicant_status'] == 'fired')--}}
                            {{--                        <span class="badge" style="background: #90ac11">{{__('messages.applicants.status.fired')}}</span>--}}
                        @elseif($recruitment['applicant_status'] == 'finished')
                            <span class="badge" style="background: #219ff3">{{__('messages.applicants.status.finished')}}</span>
                        @endif
                    </div>
                </div>
                <div class="m-2">
                    @if($recruitment['recruitment_status'] == 'collecting')
                        <a
                            href="{{ route('application_detail_view', ['applicant_id' => $recruitment['applicant_id']]) }}"
                            type="button"
                            class="btn btn-primary btn-tooltip"
                            data-bs-toggle="tooltip"
                            data-bs-placement="bottom"
                            title="{{ __('messages.recruitment.applicants_detail_tooltip') }}"
                            data-container="body"
                            data-animation="true"
                        >
                            <i class="fa fa-search text-sm"></i>
                            {{__('messages.action.view_application_status')}}
                        </a>
                        @if($recruitment['applicant_status'] == 'waiting' || $recruitment['applicant_status'] == 'approved')
                            <button
                                href=""
                                class="btn btn-outline-primary btn-tooltip"
                                data-bs-toggle="tooltip"
                                data-bs-placement="bottom"
                                title="{{ __('messages.applications.stop_tooltip') }}"
                                data-container="body"
                                data-animation="true"
                                onclick="cancel_work('{{ route('finish_matter', ['matter_id' => $recruitment['recruitment_id']]) }}')"
                            >
                                <i class="fa fa-close text-sm"></i>
                                {{__('messages.action.abandon')}}
                            </button>
                        @endif
                    @elseif($recruitment['recruitment_status'] == 'working')
                        <a
                            href="{{ route('application_detail_view', ['applicant_id' => $recruitment['applicant_id']]) }}"
                            type="button"
                            class="btn btn-primary btn-tooltip"
                            data-bs-toggle="tooltip"
                            data-bs-placement="bottom"
                            title="{{ __('messages.recruitment.applicants_detail_tooltip') }}"
                            data-container="body"
                            data-animation="true"
                        >
                            <i class="fa fa-search text-sm"></i>
                            {{__('messages.action.view_application_status')}}
                        </a>
                        @if($recruitment['applicant_status'] == 'approved')
                            <button
                                href=""
                                class="btn btn-outline-primary btn-tooltip"
                                data-bs-toggle="tooltip"
                                data-bs-placement="bottom"
                                title="{{ __('messages.applications.stop_tooltip') }}"
                                data-container="body"
                                data-animation="true"
                                onclick="cancel_work('{{ route('finish_matter', ['matter_id' => $recruitment['recruitment_id']]) }}')"
                            >
                                <i class="fa fa-close text-sm"></i>
                                {{__('messages.action.abandon')}}
                            </button>
                        @endif
                    @elseif($recruitment['recruitment_status'] == 'completed' && $recruitment['applicant_status'] == 'approved')
                        <a
                            href="{{ route('application_detail_view', ['applicant_id' => $recruitment['applicant_id']]) }}"
                            type="button"
                            class="btn btn-primary btn-tooltip"
                            data-bs-toggle="tooltip"
                            data-bs-placement="bottom"
                            title="{{ __('messages.recruitment.applicants_detail_tooltip') }}"
                            data-container="body"
                            data-animation="true"
                        >
                            <i class="fa fa-search text-sm"></i>
                            {{__('messages.action.view_application_status')}}
                        </a>
                        <a
                            href="{{ route('matter_review_view', ['matter_id' => $recruitment['recruitment_id']]) }}"
                            type="button"
                            class="btn btn-success btn-tooltip"
                            data-bs-toggle="tooltip"
                            data-bs-placement="bottom"
                            title="{{ __('messages.recruitment.review_detail_tooltip') }}"
                            data-container="body"
                            data-animation="true"
                        >
                            <i class="fa fa-pencil text-sm"></i>
                            {{__('messages.action.evaluate')}}
                        </a>
                    @elseif($recruitment['recruitment_status'] == 'canceled' || $recruitment['recruitment_status'] == 'deleted' || $recruitment['applicant_status'] == 'abandoned')
                        <a
                            href="{{ route('result_detail_view', ['applicant_id' => $recruitment['id']]) }}"
                            type="button"
                            class="btn btn-info btn-tooltip"
                            data-bs-toggle="tooltip"
                            data-bs-placement="bottom"
                            title="{{ __('messages.recruitment.result_detail_tooltip') }}"
                            data-container="body"
                            data-animation="true"
                        >
                            <i class="fa fa-th-large text-sm"></i>
                            {{__('messages.action.detail')}}
                        </a>
                    @elseif($recruitment['applicant_status'] == 'fired' || $recruitment['applicant_status'] == 'finished')
                        @if($recruitment['recruitment_review']>0 && $recruitment['recruitment_evaluation'] != null)
                            <a
                                href="{{ route('result_detail_view', ['applicant_id' => $recruitment['id']]) }}"
                                type="button"
                                class="btn btn-info btn-tooltip"
                                data-bs-toggle="tooltip"
                                data-bs-placement="bottom"
                                title="{{ __('messages.recrpuitment.result_detail_tooltip') }}"
                                data-container="body"
                                data-animation="true"
                            >
                                <i class="fa fa-th-large text-sm"></i>
                                {{__('messages.action.view_work_result')}}
                            </a>
                        @else
                            <a
                                href="{{ route('matter_review_view', ['matter_id' => $recruitment['recruitment_id']]) }}"
                                type="button"
                                class="btn btn-warning btn-tooltip"
                                data-bs-toggle="tooltip"
                                data-bs-placement="bottom"
                                title="{{ __('messages.recruitment.review_detail_tooltip') }}"
                                data-container="body"
                                data-animation="true"
                            >
                                <i class="fa fa-pencil text-sm"></i>
                                {{__('messages.action.evaluate')}}
                            </a>
                        @endif
                    @endif
                    <a
                        type="button"
                        id="{{$recruitment['recruitment_id']}}"
                        class="btn {{$recruitment['is_favourite']?'btn-success':'btn-outline-success'}} favourite-btn"
                        data-bs-toggle="tooltip"
                        data-bs-placement="bottom"
                        title="{{ __('messages.matters.favourite_tooltip') }}"
                        data-container="body"
                        data-animation="true"
                    >
                        <i class="fa fa-bookmark text-sm"></i>
                        {{__('messages.action.favourite')}}
                    </a>
                </div>
            </div>
        </div>
    </div>
    <form method="POST" id="cancel_form">
        @csrf
        @method('PUT')
        <input type="hidden" name="status" id="status" value="abandoned">
        <input type="hidden" name="recruitment_evaluation" id="recruitment_evaluation" />
    </form>
@endforeach

{{ $recruitments->links('vendor.pagination.ajax') }}

<script>
    function cancel_work(url) {
        swal({
            title: "{{__('messages.alert.are_you_sure_to_abandon_work')}}",
            text: "<textarea type='text' id='comment_text' rows='5' style='border: 1px solid grey; border-radius: 5px; width: 100%; padding: 5px' placeholder='{{__('messages.alert.type_comment')}}'></textarea>",
            html: true,
            showCancelButton: true,
            closeOnConfirm: false,
            confirmButtonText: "{{__('messages.action.yes')}}",
            cancelButtonText: "{{__('messages.action.no')}}",
        }, function(){
            var comment = $("#comment_text").val();
            $("#recruitment_evaluation").val(comment);
            $('#cancel_form').attr('action', url).submit();
        });
    }

    $(".favourite-btn").click(function () {
        var clicked_btn = $(this);
        var is_favourite = $(this).hasClass('btn-success');

        $.ajax({
            url: is_favourite ? "{{ route('unset_recruitment_favourite') }}" : "{{ route('set_recruitment_favourite') }}",
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
                    if(is_favourite) clicked_btn.removeClass('btn-success').addClass('btn-outline-success');
                    else clicked_btn.removeClass('btn-outline-success').addClass('btn-success');
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
