@extends('layouts.dashboard')

@section('links')
    @include('css.links')
@endsection

@section('content')
    <div class="m-3">
        <h3>{{__('messages.title.my_detail')}}</h3>
    </div>

    <x-worker :worker="$worker"></x-worker>

    <div class="mx-3 mt-5">
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
            <div class="col-lg-1 col-md-3 col-sm-12 col-xs-4 text-center">
                <img
                    src="{{ !empty($recruitment['image']) ? asset('uploads/recruitments/'.$recruitment['image']) : asset('assets/img/utils/empty.png') }}"
                    class="avatar avatar-xl me-3"
                >
            </div>
            <div class="col-lg-9 col-md-6 col-sm-12 col-xs-12">
                <p>
                    <span>
                        <a href="{{ route('result_detail_view', $recruitment['id']) }}" class="text-primary text-bold m-0">
                            {{ $recruitment['title'] }}
                        </a>
                    </span>
                    <span>
                        <a href="{{ route('producer_detail_view', $recruitment['producer_id']) }}" class="text-info text-decoration-underline text-bold m-0">
                            ({{ $recruitment['family_name'] }})
                        </a>
                    </span>
                </p>
                <p class="m-0">
                    {{ $recruitment['worker_evaluation'] ? $recruitment['worker_evaluation'] : __('messages.applications.no_worker_evaluation') }}
                </p>
                <input value="{{ $recruitment['worker_review'] }}" type="text" class="rating" data-size="xs" readonly>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12">
                <p class="text-sm text-end m-0">
                    <i class="fa fa-calendar"></i>
                    {{ format_date($recruitment['updated_at']) }}</p>
            </div>
        </div>
        <hr class="horizontal dark mt-1" style="height: 2px;"/>
    @endforeach

    {{ $recruitments->links('vendor.pagination.custom') }}

@endsection

@section('scripts')
    @include('scripts.scripts')
@endsection
