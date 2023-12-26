@extends('layouts.dashboard')

@section('links')
    @include('css.links')
@endsection

@section('content')
    <div class="mb-3">
        <h3> {{ __('messages.title.recruitment_detail') }} </h3>
    </div>

    <x-matter :recruitment="$recruitment" />

    <h4>{{__('messages.applicants.applicant_list')}}</h4>

    @unless(count($applicants) > 0)
        <div class="card text-center m-5">
            <i class="fa fa-exclamation-triangle" style="font-size: 60px"></i>
            <p class="text-center text-bold m-3"> {{__('messages.applicants.no_applicants')}} </p>
        </div>
    @endunless

    @foreach ($applicants as $applicant)
        <hr class="horizontal dark mt-1" style="height: 2px;"/>
        <div class="row m-0">
            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4 text-center">
                <img
                    src="{{ $applicant['avatar'] === 'default.png' ? asset('assets/img/utils/default.png') : asset('avatars/'.$applicant['avatar']) }}"
                    class="avatar avatar-lg"
                    data-bs-toggle="tooltip"
                    data-bs-placement="bottom"
                    title="{{ $applicant['appeal_point'] }}"
                    data-container="body"
                    data-animation="true"
                >
                <h6 class="mb-0 text-xs">{{ $applicant['nickname'] }}</h6>
                <input value="{{ $applicant['review'] }}" type="text" class="rating" data-size="xs" readonly>
            </div>
            <div class="col-lg-10 col-md-9 col-sm-8 col-xs-8">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                        <p class="m-0 text-dark">
                            <span class="text-bold text-sm text-success">
                                {{__('messages.profile.age')}}:
                            </span>
                            {{ date_diff(date_create($applicant['birthday']), date_create(date("Y-m-d")))->format('%y') }}歳
                        </p>
                        <p class="m-0 text-dark">
                            <span class="text-bold text-sm text-success">
                                {{__('messages.profile.job')}}:
                            </span>
                            {{ $applicant['job'] }}
                        </p>
                    </div>
                    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                        <p class="m-0 text-dark">
                            <span class="text-bold text-sm text-success">
                                {{__('messages.profile.bio')}}:
                            </span>
                            {{ $applicant['bio'] }}
                        </p>
                        <p class="m-0 text-dark">
                            <span class="text-bold text-sm text-success">
                                {{__('messages.applicants.apply_memo')}}:
                            </span>
                            {{ $applicant['apply_memo'] }}
                        </p>
                        <p class="m-0 text-dark">
                            <span class="text-bold text-sm text-success">
                                {{__('messages.applicants.status.title')}}:
                            </span>
                            @if($applicant['status'] === 'waiting')
                                <span class="badge" style="background: #8058ef">{{__('messages.applicants.status.waiting')}}</span>
                            @elseif($applicant['status'] === 'approved')
                                <span class="badge" style="background: #069367">{{__('messages.applicants.status.approved')}}</span>
                            @elseif($applicant['status'] === 'abandoned')
                                <span class="badge" style="background: #ac3d11">{{__('messages.applicants.status.abandoned')}}</span>
                            @elseif($applicant['status'] === 'rejected')
                                <span class="badge" style="background: #fd8503">{{__('messages.applicants.status.rejected')}}</span>
                            @elseif($applicant['status'] === 'finished')
                                <span class="badge" style="background: #219ff3">{{__('messages.applicants.status.finished')}}</span>
                                {{--                            @elseif($applicant['status'] === 'fired')--}}
                                {{--                                <span class="m-0">{{__('messages.applicants.status.fired')}}</span>--}}
                            @endif
                        </p>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="m-0 text-dark">
                            <span class="text-bold text-sm text-primary">
                                {{$applicant['status'] == 'abandoned' ?  __('messages.applications.worker_comment') : __('messages.applications.worker_evaluation')}}:
                            </span>
                            @if($applicant['recruitment_evaluation'])
                                {{$applicant['recruitment_evaluation']}}
                            @else
                                {{__('messages.applications.no_recruitment_evaluation')}}
                            @endif
                        </div>
                        <div class="m-0 text-dark d-flex">
                            <span class="text-bold text-sm text-primary mt-1">
                                {{__('messages.applications.worker_review')}} :
                            </span>
                            @if($applicant['recruitment_review'])
                                <div class="d-inline">
                                    <input value="{{$applicant['recruitment_review']}}" type="text" class="rating" data-size="xs" readonly>
                                </div>
                            @else
                                {{__('messages.applications.no_rank')}}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr class="horizontal dark mt-1" style="height: 2px;"/>
    @endforeach

    {{ $applicants->links('vendor.pagination.custom') }}

    <div class="col-12 text-end">
        <a type="button" class="btn btn-secondary m-2" href="{{ route('recruitment_status_view', ['status' => $recruitment['status']]) }}">
            <i class="fa fa-arrow-left"></i> {{__('messages.action.back')}}
        </a>
    </div>
@endsection

@section('scripts')
    @include('scripts.scripts')
@endsection
