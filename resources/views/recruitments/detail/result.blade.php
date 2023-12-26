@extends('layouts.dashboard')

@section('links')
    @include('css.links')
@endsection

@section('content')

    <div class="mb-4">
        <h3>{{$recruitment['status'] == 'canceled' ? __('messages.title.recruitment_canceled') : $recruitment['status'] == 'deleted' ? __('messages.title.recruitment_deleted') : __('messages.title.recruitment_result')}}</h3>
    </div>

    <x-recruitment :recruitment="$recruitment" :count="$applicants_count"/>

    @unless(count($applicants))
        <p class="text-center m-3"> {{__('messages.title.no_data')}} </p>
    @endunless

    @foreach ($applicants as $applicant)
        <hr class="horizontal dark mt-1" style="height: 2px;"/>
        <div class="row m-0">
            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4 text-center">
                <img src="{{ $applicant['avatar'] === 'default.png' ? asset('assets/img/utils/default.png') : asset('avatars/'.$applicant['avatar']) }}" class="avatar avatar-md inline">
                <p class="text-xs text-secondary mt-2 mb-0">{{ $applicant['nickname'] }}</p>
            </div>
            <div class="col-lg-10 col-md-9 col-sm-8 col-xs-8">
                <div class="row">
                    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                        <h6>{{__('messages.applications.producer_evaluation')}}</h6>
                        <p class="text-sm m-0">{{ $applicant['worker_evaluation'] ? $applicant['worker_evaluation'] : __('messages.applications.no_worker_evaluation') }}</p>
                        <input value="{{ $applicant['worker_review'] }}" type="text" class="rating" data-size="xs" readonly>
                    </div>
                    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                        <h6>{{__('messages.applications.worker_evaluation')}}</h6>
                        <p class="text-sm m-0">{{ $applicant['recruitment_evaluation'] ? $applicant['recruitment_evaluation'] : __('messages.applications.no_recruitment_evaluation') }}</p>
                        <input value="{{ $applicant['recruitment_review'] }}" type="text" class="rating" data-size="xs" readonly>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 text-center">
                        @if($recruitment['status'] == 'canceled')
                            <span class="text-warning">{{__('messages.recruitment.status.canceled')}}</span>
                        @else
                            @if($applicant['status'] == 'waiting')
                                <span class="badge" style="background: #8058ef">{{__('messages.applicants.status.waiting')}}</span>
                            @elseif($applicant['status'] == 'approved')
                                <span class="badge" style="background: #069367">{{__('messages.applicants.status.approved')}}</span>
                            @elseif($applicant['status'] == 'rejected')
                                <span class="badge" style="background: #fd8503">{{__('messages.applicants.status.rejected')}}</span>
                            @elseif($applicant['status'] == 'abandoned')
                                <span class="badge" style="background: #ac3d11">{{__('messages.applicants.status.abandoned')}}</span>
                            @elseif($applicant['status'] == 'finished')
                                <span class="badge" style="background: #219ff3">{{__('messages.applicants.status.finished')}}</span>
                            @endif
                        @endif
                        <button class="btn btn-sm favourite-btn m-1 {{$applicant['is_favourite']?'btn-success':'btn-outline-success'}}" id="{{$applicant['user_id']}}">
                            <i class="fa fa-bookmark"></i> {{__('messages.action.favourite')}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <hr class="horizontal dark mt-1" style="height: 2px;"/>
    @endforeach

    {{ $applicants->links('vendor.pagination.custom') }}


    <div class="m-3" style="text-align: right">
        <button type="button" class="btn btn-secondary m-2" style="float: right" onclick="javascript:history.go(-1);"> <i class="fa fa-arrow-left"></i>
            {{__('messages.action.back')}} </button>
    </div>

@endsection

@section('scripts')
    @include('scripts.scripts')
    <script>
        $(".favourite-btn").click(function () {
            var clicked_btn = $(this);
            var is_favourite = $(this).hasClass('btn-success');

            $.ajax({
                url: is_favourite ? "{{ route('unset_favourite') }}" : "{{ route('set_favourite') }}",
                type: "PUT",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    favourite_id: this.id
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
@endsection
