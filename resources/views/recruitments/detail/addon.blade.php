@extends('layouts.dashboard')

@section('links')
    @include('css.links')
@endsection

@section('content')
    <div class="mb-4">
        <h3>{{__('messages.title.recruitment_addon')}}</h3>
    </div>

    <x-recruitment :recruitment="$recruitment" :count="$applicants_count"/>

    @unless(count($recruitment['postscript']))
        <div class="alert alert-info" role="alert">
            <span class="alert-text text-white"> {{__('messages.title.no_addon')}} </span>
        </div>
    @endunless

    @foreach($recruitment['postscript'] as $postscript)
        <div class="card card-frame m-2">
            <div class="card-body">
                <pre>{{ $postscript['content'] }}</pre>
                <p class="text-sm">
                    <i class="material-icons text-sm me-1">schedule</i> {{ $postscript['time'] }}
                </p>
            </div>
        </div>
    @endforeach

    <form enctype='multipart/form-data' method="POST" action="{{ route('add_recruitment_addon', ['recruitment_id' => $recruitment['id']]) }}" id="form" autocomplete="off">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-12">
                <div class="input-group input-group-outline mt-3">
                    <textarea name="postscript" class="form-control" type="text" rows="5" placeholder="{{__('messages.recruitment.postscript')}}">{{ old('postscript') }}</textarea>
                    @error('postscript')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-success m-2"> <i class="fa fa-save"></i> {{__('messages.action.save')}} </button>
                <button type="button" class="btn btn-secondary m-2" onclick="javascript:history.go(-1);"> <i class="fa fa-arrow-left"></i>
                    {{__('messages.action.back')}} </button>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    @include('scripts.scripts')
@endsection
