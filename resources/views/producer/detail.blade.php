@extends('layouts.dashboard')

@section('links')
    @include('css.links')
@endsection

@section('content')
    <div class="m-3">
        <h3>{{ Auth::user()->role == 'worker' ?  __('messages.profile.producer_info') : __('messages.title.my_detail') }}</h3>
    </div>

    <div class="card">
        <x-producer :producer="$producer"></x-producer>
    </div>

    <div class="m-3">
        <h4>{{__('messages.profile.matching_history')}}</h4>
    </div>

    @unless(count($recruitments))
        <div class="card text-center m-5">
            <i class="fa fa-exclamation-triangle" style="font-size: 60px"></i>
            <p class="text-center text-bold m-3"> {{__('messages.recruitment.no_data')}} </p>
        </div>
    @endunless

    @foreach ($recruitments as $recruitment)
        <hr class="horizontal dark mt-1" style="height: 2px;"/>
        <div class="row">
            <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12 text-center">
                <img
                    src="{{ !empty($recruitment['image']) ? asset('uploads/recruitments/'.$recruitment['image']) : asset('assets/img/utils/empty.png') }}"
                    class="avatar avatar-xl me-3"
                >
                <input value="{{ $recruitment['review'] }}" type="text" class="rating" data-size="xs" readonly>
            </div>
            <div class="col-lg-8 col-md-5 col-sm-12 col-xs-12">
                @if(Auth::user()->role == 'producer')
                    <a href="{{ route('recruitment_result_view', $recruitment['id']) }}" class="text-primary text-bold m-0">
                        {{ $recruitment['title'] }}
                    </a>
                @else
                    <p class="text-primary text-bold m-0">
                        {{ $recruitment['title'] }}
                    </p>
                @endif
                <p class="text-sm toggle_review cursor-pointer text-primary" target="review{{$recruitment['id']}}" status="show">
                    {{__('messages.action.show_comment')}}
                </p>
                <div id="review{{$recruitment['id']}}">
                    @foreach($recruitment['applicants'] as $applicant)
                        <div class="row">
                            <div class="col-md-9 d-flex align-items-center">
                                <div>
                                    <img src="{{ $applicant['avatar'] === 'default.png' ? asset('assets/img/utils/default.png') : asset('avatars/'.$applicant['avatar']) }}" class="avatar avatar-md me-3 inline">
                                    <p class="text-sm">
                                        {{ $applicant['nickname'] }}
                                    </p>
                                </div>
                                <div>
                                    <div class="d-flex">
                                        <p class="text-bold">
                                            {{__('messages.profile.age')}}:
                                        </p>
                                        <p>
                                            {{ \Carbon\Carbon::parse($applicant['birthday'])->diff(\Carbon\Carbon::now())->y }}
                                        </p>
                                    </div>
                                    <p class="text-sm">
                                        {{ $applicant['recruitment_evaluation'] ? $applicant['recruitment_evaluation'] : __('messages.applications.no_recruitment_evaluation') }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <input value="{{ $applicant['recruitment_review'] }}" type="text" class="rating" data-size="xs" readonly>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                <p class="text-sm text-end m-0">
                    <i class="fa fa-calendar"></i>
                    {{ format_date($recruitment['updated_at']) }}</p>
            </div>
        </div>
        <hr class="horizontal dark mt-1" style="height: 2px;"/>
    @endforeach

    {{ $recruitments->links('vendor.pagination.custom') }}

    <div class="m-3" style="text-align: right">
        <button type="button" class="btn btn-secondary m-2" onclick="javascript:history.go(-1);"> <i class="fa fa-arrow-left"></i>
            {{__('messages.action.back')}} </button>
    </div>
@endsection

@section('scripts')
    @include('scripts.scripts')
    <script>
        $(".toggle_review").click(function () {
            if($(this).attr('status') === 'hide') {
                $("#"+$(this).attr('target')).show();
                $(this).attr('status', 'show');
                $(this).text("{{__('messages.action.hide_comment')}}");
            }
            else {
                $("#"+$(this).attr('target')).hide();
                $(this).attr('status', 'hide');
                $(this).text("{{__('messages.action.show_comment')}}");
            }
        })
    </script>
@endsection
