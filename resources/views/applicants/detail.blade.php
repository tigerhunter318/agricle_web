@extends('layouts.dashboard')

@section('links')
    @include('css.links')
@endsection

@section('content')

    <div class="mb-4">
        <h3>{{__('messages.applications.detail')}}</h3>
    </div>

    <div class="row justify-space-between py-2">
        <div class="col-lg-6 mx-auto">
            <div class="nav-wrapper position-relative end-0">
                <ul class="nav nav-pills nav-fill p-1" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link mb-0 px-0 py-1 d-flex align-items-center justify-content-center text-lg text-primary text-bold active" data-bs-toggle="tab" href="#matter_info_tab" role="tab" aria-controls="preview" aria-selected="true">
                            <i class="material-icons text-sm me-2">dashboard</i> {{__('messages.matters.matter_info')}}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mb-0 px-0 py-1 d-flex align-items-center justify-content-center text-lg text-info text-bold" data-bs-toggle="tab" href="#applicants_info_tab" role="tab" aria-controls="code" aria-selected="false">
                            <i class="material-icons text-sm me-2">file_present</i> {{__('messages.applications.detail')}}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="tab-content" id="matter_content">
            <div class="tab-pane fade show active" id="matter_info_tab" role="tabpanel" aria-labelledby="matter_info_tab">
                <div class="m-3">
                    <x-producer :producer="$recruitment['producer']" />
                    <hr>
                    <x-matter :recruitment="$recruitment" />
                </div>
            </div>
            <div class="tab-pane fade" id="applicants_info_tab" role="tabpanel" aria-labelledby="applicants_info_tab">
                <div class="mt-lg-5 mt-4">
                    <div class="row">
                        <div class="col-6">
                            <h4>{{__('messages.applicants.apply_memo')}}</h4>
                            <p>
                                {{ $recruitment['applicant']['apply_memo'] ? $recruitment['applicant']['apply_memo'] : __('messages.applicants.no_apply_memo') }}
                            </p>
                        </div>
                        <div class="col-6">
                            <h4>{{__('messages.applicants.employ_memo')}}</h4>
                            <p>
                                {{ $recruitment['applicant']['employ_memo'] ? $recruitment['applicant']['employ_memo'] : __('messages.applicants.no_employ_memo') }}
                            </p>
                        </div>
                    </div>

                    <h4>{{__('messages.applicants.status.title')}}</h4>
                    @if($recruitment['applicant']['status'] == 'waiting')
                        <span class="badge" style="background: #8058ef">{{__('messages.applicants.status.waiting')}}</span>
                    @elseif($recruitment['applicant']['status'] == 'approved')
                        <span class="badge" style="background: #069367">{{__('messages.applicants.status.approved')}}</span>
                    @elseif($recruitment['applicant']['status'] == 'rejected')
                        <span class="badge" style="background: #fd8503">{{__('messages.applicants.status.rejected')}}</span>
                    @elseif($recruitment['applicant']['status'] == 'abandoned')
                        <span class="badge" style="background: #ac3d11">{{__('messages.applicants.status.abandoned')}}</span>
                    @elseif($recruitment['applicant']['status'] == 'finished')
                        <span class="badge" style="background: #219ff3">{{__('messages.applicants.status.finished')}}</span>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <div class="m-3" style="text-align: right">
        <button type="button" class="btn btn-secondary m-2" style="float: right" onclick="javascript:history.go(-1);"> <i class="fa fa-arrow-left"></i>
            {{__('messages.action.back')}} </button>
    </div>

@endsection

@section('scripts')
    @include('scripts.scripts')
@endsection
