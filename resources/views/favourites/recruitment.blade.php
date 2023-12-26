@extends('layouts.dashboard')

@section('links')
    @include('css.links')
@endsection

@section('content')
    <div class="mb-3">
        <h3> {{ __('messages.header.favourite_recruitment') }} </h3>
    </div>

    <x-matter :recruitment="$recruitment" />

    <div class="col-12 text-end">
        <button type="button" class="btn btn-secondary m-2" onclick="javascript:history.go(-1);">
            <i class="fa fa-arrow-left"></i> {{__('messages.action.back')}}
        </button>
    </div>
@endsection

@section('scripts')
    @include('scripts.scripts')
    <script>
        $(".favourite-btn").click(function () {
            $.ajax({
                url: "{{ route('unset_recruitment_favourite') }}",
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
                        location.reload();
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
