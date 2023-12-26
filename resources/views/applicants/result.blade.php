@extends('layouts.dashboard')

@section('links')
    @include('css.links')
@endsection

@section('content')
    <div class="mb-4">
        <h3>{{__('messages.title.recruitment_result')}}</h3>
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
                            <i class="material-icons text-sm me-2">file_present</i> {{__('messages.applications.result')}}
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
                <div class="row mt-lg-5 mt-4">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="card">
                            <div class="ps-4 mt-n4">
                                <div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary shadow text-center border-radius-xl">
                                    <i class="material-icons opacity-10">thumb_up_alt</i>
                                </div>
                            </div>
                            <div class="card-body border-radius-lg position-relative overflow-hidden pb-4">
                                <h5 class="mt-2">{{__('messages.applications.producer_evaluation')}}</h5>
                                <p class="mb-3">
                                    {{ $applicant['worker_evaluation'] ? $applicant['worker_evaluation'] : __('messages.applications.no_worker_evaluation') }}
                                </p>
                                <input value="{{ $applicant['worker_review'] }}" type="text" class="rating" data-size="md" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="card mb-3">
                            <div class="ps-4 mt-n4">
                                <div class="icon icon-lg icon-shape bg-gradient-success shadow-success shadow text-center border-radius-xl">
                                    <i class="material-icons opacity-10">thumb_up_alt</i>
                                </div>
                            </div>
                            <div class="card-body border-radius-lg position-relative overflow-hidden pb-4">
                                <h5 class="mt-2">{{__('messages.applications.worker_evaluation')}}</h5>
                                <p class="mb-3">
                                    {{ $applicant['recruitment_evaluation'] ? $applicant['recruitment_evaluation'] : __('messages.applications.no_recruitment_evaluation') }}
                                </p>
                                <input value="{{ $applicant['recruitment_review'] }}" type="text" class="rating" data-size="md" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="m-3" style="text-align: right">
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
        <button type="button" class="btn btn-secondary" onclick="javascript:history.go(-1);"> <i class="fa fa-arrow-left"></i>
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
@endsection
