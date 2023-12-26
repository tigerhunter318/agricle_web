@extends('layouts.dashboard')

@section('links')
    @include('css.links')
@endsection

@section('content')
    <div class="mb-4">
        <h3>{{__('messages.applicants.confirm_status')}}</h3>
    </div>

    <div class="row d-flex justify-content-between">
        <div class="col-lg-3 col-md-5 col-sm-6">
            <div class="input-group input-group-outline">
                <select name="status" id="status" class="form-control" onchange="search_application('filter')">
                    <option value="all">{{__('messages.applications.status_all')}}</option>
                    <option value="waiting">{{__('messages.applicants.status.waiting')}}</option>
                    <option value="approved">{{__('messages.applicants.status.approved')}}</option>
                    <option value="rejected">{{__('messages.applicants.status.rejected')}}</option>
                    <option value="abandoned">{{__('messages.applicants.status.abandoned')}}</option>
                    <option value="finished">{{__('messages.applicants.status.finished')}}</option>
                    <option value="canceled">{{__('messages.recruitment.status.canceled')}}</option>
                    <option value="deleted">{{__('messages.recruitment.status.deleted')}}</option>
{{--                    <option value="fired">{{__('messages.applicants.status.fired')}}</option>--}}
                </select>
            </div>
        </div>
        <div class="col-lg-3 col-md-5 col-sm-6">
            <div class="input-group input-group-outline">
                <input type="text" class="form-control" name="keyword" id="keyword">
                <span class="input-group-text p-1 z-index-3">
                    <button class="btn btn-primary btn-sm my-0 mx-1" onclick="search_application('filter')">
                        <i class="fa fa-search text-md text-white"></i>
                    </button>
                </span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12" id="applications_body">
        </div>
    </div>
@endsection

@section('scripts')
    @include('scripts.scripts')
    <script>
        var page = 1;

        $(document).ready(function(){
            search_application();
        });

        $("#keyword").on('keypress', function (e) {
            if(e.keyCode === 13) search_application('filter')
        })

        // type is able to be 'init', 'filter', 'paginate'
        function search_application(type = 'init') {
            var data = {};
            if(type === 'filter') page = 1;
            if(type !== 'init') {
                data = {
                    keyword: $("#keyword").val(),
                    status: $("#status").val(),
                    page: page
                }
            }
            $.ajax({
                url: "{{ route('search_application') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                data: data,
                success: function (response) {
                    $("#applications_body").html(response);
                }
            });
        }

        function reset() {
            page = 1;
            $('input[type=checkbox]:checked').prop('checked', false);
            handle_custom();
            search_application();
        }

        $(document).on('click', '.page-link', function () {
            var target_page;
            var current_page = $('.page-item.active>a').attr('id');
            var page_count = $('.page-item').length - 2;

            if(!!this.id) target_page = parseInt(this.id);
            else {
                if($(this)[0].ariaLabel === 'Next') target_page = parseInt(current_page) + 1;
                else target_page = parseInt(current_page) - 1;
            }

            page = target_page;
            search_application('paginate');

            // unset disabled or active all page-link
            $('.page-item').removeClass('disabled').removeClass('active');

            // set disabled if target is first or last
            if(parseInt(target_page) === 1) $('.page-item:first').addClass('disabled');
            else if(parseInt(page_count) === parseInt(target_page)) $('.page-item:last').addClass('disabled');

            // set active target page-link
            $('.page-item:nth-child('+(target_page+1)+')').addClass('active');//.addClass('disabled');
        })

    </script>
@endsection
