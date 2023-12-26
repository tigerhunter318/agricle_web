{{ $recruitments->links('vendor.pagination.ajax') }}

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
                    <a
                        href="{{ route('producer_detail_view', $recruitment->user_id) }}"
                        class="text-success text-md"
                        data-bs-toggle="tooltip"
                        data-bs-placement="bottom"
                        title="{{ __('messages.matters.producer_title_tooltip') }}"
                        data-container="body"
                        data-animation="true"
                    >
{{--                        ({{ __('messages.profile.producer_info') }})--}}
                        ({{ $recruitment->family_name }})
                    </a>
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
            </div>
        </div>
        <div class="text-center mx-2">
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
            <a
                href="{{ route('producer_detail_view', $recruitment->user_id) }}"
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
@endforeach

{{ $recruitments->links('vendor.pagination.ajax') }}

<script>
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
