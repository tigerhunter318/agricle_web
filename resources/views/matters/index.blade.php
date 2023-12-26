@extends('layouts.dashboard')

@section('links')
    @include('css.links')
@endsection

@section('content')
    <div class="mb-3">
        <h3>案件案内［一覧］</h3>
    </div>

    <div class="row">
        <div class="col-lg-3 col-md-4 col-sm-12 p-2">
            <div class="card bg-gray-100 p-3">
                <div class="input-group input-group-outline mb-3">
                    <label class="form-label"> {{__('messages.matters.search_label')}} </label>
                    <input type="text" class="form-control" name="keyword" id="keyword">
                    <span class="input-group-text p-1 z-index-3">
                        <button class="btn btn-primary btn-sm my-0 mx-1" onclick="search_matter('filter')">{{__('messages.action.search')}}</button>
                    </span>
                </div>
                <div class="accordion px-2">
                    <div class="accordion-item mb-3">
                        <h6 class="accordion-header" id="heading1">
                            <button class="accordion-button border-bottom font-weight-bold text-start py-1" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="false" aria-controls="collapse1">
                                {{__('messages.recruitment.reward.title')}}
                                <i class="collapse-close fa fa-plus text-xs pt-1 position-absolute end-0" aria-hidden="true"></i>
                                <i class="collapse-open fa fa-minus text-xs pt-1 position-absolute end-0" aria-hidden="true"></i>
                            </button>
                        </h6>
                        <div id="collapse1" class="accordion-collapse collapse" aria-labelledby="heading1" data-bs-parent="#search_collapse">
                            <div class="accordion-body text-sm opacity-8 p-1">
                                <div class="form-check my-2">
                                    <input class="form-check-input" type="checkbox" id="cash" name="cash" >
                                    <label class="form-check-label" for="cash">{{__('messages.recruitment.pay_mode.cash')}}</label>
                                </div>
                                <div class="form-check my-2">
                                    <input class="form-check-input" type="checkbox" id="card" name="card" >
                                    <label class="form-check-label" for="card">{{__('messages.recruitment.pay_mode.card')}}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item mb-3">
                        <h6 class="accordion-header" id="heading2">
                            <button class="accordion-button border-bottom font-weight-bold text-start py-1" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                                {{__('messages.recruitment.environment')}}
                                <i class="collapse-close fa fa-plus text-xs pt-1 position-absolute end-0" aria-hidden="true"></i>
                                <i class="collapse-open fa fa-minus text-xs pt-1 position-absolute end-0" aria-hidden="true"></i>
                            </button>
                        </h6>
                        <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="heading2" data-bs-parent="#search_collapse">
                            <div class="accordion-body text-sm opacity-8 p-1">
                                <div class="form-check my-2">
                                    <input class="form-check-input" type="checkbox" id="traffic_cost" name="traffic_cost" >
                                    <label class="form-check-label" for="traffic_cost">{{__('messages.recruitment.traffic.title')}}</label>
                                </div>
                                <div class="form-check my-2">
                                    <input class="form-check-input" type="checkbox" id="toilet" name="toilet" >
                                    <label class="form-check-label" for="toilet">{{__('messages.recruitment.toilet.title')}}</label>
                                </div>
                                <div class="form-check my-2">
                                    <input class="form-check-input" type="checkbox" id="park" name="park" >
                                    <label class="form-check-label" for="park">{{__('messages.recruitment.park.title')}}</label>
                                </div>
                                <div class="form-check my-2">
                                    <input class="form-check-input" type="checkbox" id="insurance" name="insurance" >
                                    <label class="form-check-label" for="insurance">{{__('messages.recruitment.insurance.title')}}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item mb-3">
                        <h6 class="accordion-header" id="heading3">
                            <button class="accordion-button border-bottom font-weight-bold text-start py-1" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                                {{__('messages.recruitment.period.title')}}
                                <i class="collapse-close fa fa-plus text-xs pt-1 position-absolute end-0" aria-hidden="true"></i>
                                <i class="collapse-open fa fa-minus text-xs pt-1 position-absolute end-0" aria-hidden="true"></i>
                            </button>
                        </h6>
                        <div id="collapse3" class="accordion-collapse collapse" aria-labelledby="heading3" data-bs-parent="#search_collapse">
                            <div class="accordion-body text-sm opacity-8 p-1">
                                <div class="form-check my-2">
                                    <input class="form-check-input" type="checkbox" id="day1" name="day1" >
                                    <label class="form-check-label" for="day1">{{__('messages.recruitment.period.day1')}}</label>
                                </div>
                                <div class="form-check my-2">
                                    <input class="form-check-input" type="checkbox" id="day2_3" name="day2_3" >
                                    <label class="form-check-label" for="day2_3">{{__('messages.recruitment.period.day2_3')}}</label>
                                </div>
                                <div class="form-check my-2">
                                    <input class="form-check-input" type="checkbox" id="in_week" name="in_week" >
                                    <label class="form-check-label" for="in_week">{{__('messages.recruitment.period.in_week')}}</label>
                                </div>
                                <div class="form-check my-2">
                                    <input class="form-check-input" type="checkbox" id="week_month" name="week_month" >
                                    <label class="form-check-label" for="week_month">{{__('messages.recruitment.period.week_month')}}</label>
                                </div>
                                <div class="form-check my-2">
                                    <input class="form-check-input" type="checkbox" id="month1_3" name="month1_3" >
                                    <label class="form-check-label" for="month1_3">{{__('messages.recruitment.period.month1_3')}}</label>
                                </div>
                                <div class="form-check my-2">
                                    <input class="form-check-input" type="checkbox" id="half_year" name="half_year" >
                                    <label class="form-check-label" for="half_year">{{__('messages.recruitment.period.half_year')}}</label>
                                </div>
{{--                                <div class="form-check my-2">--}}
{{--                                    <input class="form-check-input" type="checkbox" id="year" name="year" >--}}
{{--                                    <label class="form-check-label" for="year">{{__('messages.recruitment.period.year')}}</label>--}}
{{--                                </div>--}}
{{--                                <div class="form-check my-2">--}}
{{--                                    <input class="form-check-input" type="checkbox" id="more_year" name="more_year" >--}}
{{--                                    <label class="form-check-label" for="more_year">{{__('messages.recruitment.period.more_year')}}</label>--}}
{{--                                </div>--}}
{{--                                <div class="form-check my-2">--}}
{{--                                    <input class="form-check-input" type="checkbox" id="custom" name="custom" onchange="handle_custom()">--}}
{{--                                    <label class="form-check-label" for="custom">{{__('messages.recruitment.period.custom')}}</label>--}}
{{--                                </div>--}}
{{--                                <div class="input-group input-group-static mx-4" id="work_date_group" style="display: none">--}}
{{--                                    <label>{{__('messages.recruitment.work_date')}}</label>--}}
{{--                                    <input type="text" class="form-control" name="work_date" id="work_date">--}}
{{--                                </div>--}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <button class="btn bg-gradient-dark btn-icon" type="button" onclick="reset()">
                        <div class="d-flex align-items-center">
                            <i class="material-icons me-2" aria-hidden="true">rotate_right</i>
                            {{__('messages.action.reset')}}
                        </div>
                    </button>
                    <button class="btn bg-gradient-primary btn-icon" type="button" onclick="search_matter('filter')">
                        <div class="d-flex align-items-center">
                            <i class="material-icons me-2" aria-hidden="true">filter_alt</i>
                            {{__('messages.action.filter')}}
                        </div>
                    </button>
                </div>
            </div>
        </div>

        <div class="col-lg-9 col-md-8 col-sm-12" id="matters_body">
        </div>
    </div>

@endsection

@section('scripts')
    @include('scripts.scripts')
    <script>
        var page = 1;

        $('#work_date').daterangepicker({
            "timePicker": false,
            "showCustomRangeLabel": false,
            "timePicker24Hour": true,
            "applyButtonClasses": "btn-primary mt-3",
            "cancelButtonClasses": "btn-default mt-3",
            "locale": {
                "format": 'YYYY/MM/DD',
                "separator": " - ",
                "applyLabel": "{{__('messages.action.yes')}}",
                "cancelLabel": "{{__('messages.action.no')}}",
                "daysOfWeek": [
                    '日',
                    '月',
                    '火',
                    '水',
                    '木',
                    '金',
                    '土',
                ],
                "monthNames": [
                    "1月",
                    "2月",
                    "3月",
                    "4月",
                    "5月",
                    "6月",
                    "7月",
                    "8月",
                    "9月",
                    "10月",
                    "11月",
                    "12月",
                ],
                "firstDay": 1
            }
        });

        $(document).ready(function(){
            search_matter();
        });

        function handle_custom() {
            // if($("#custom")[0].checked) {
            //     $("#work_date_group").removeAttr('style');
            // }
            // else {
            //     $("#work_date_group").css('display', 'none');
            // }
        }

        // type is able to be 'init', 'filter', 'paginate'
        function search_matter(type = 'init') {
            var work_date_start = '';
            var work_date_end = '';
            if($("#work_date").val()) {
                work_date_start = $("#work_date").data('daterangepicker').startDate.format('YYYY/MM/DD');
                work_date_end = $("#work_date").data('daterangepicker').endDate.format('YYYY/MM/DD');
            }
            var data = {};
            if(type === 'filter') page = 1;
            if(type !== 'init') {
                data = {
                    keyword: $("#keyword").val(),
                    cash: $("#cash")[0].checked,
                    card: $("#card")[0].checked,
                    traffic_cost: $("#traffic_cost")[0].checked,
                    toilet: $("#toilet")[0].checked,
                    insurance: $("#insurance")[0].checked,
                    park: $("#park")[0].checked,

                    day1: $("#day1")[0].checked,
                    day2_3: $("#day2_3")[0].checked,
                    in_week: $("#in_week")[0].checked,
                    week_month: $("#week_month")[0].checked,
                    month1_3: $("#month1_3")[0].checked,
                    // half_year: $("#half_year")[0].checked,
                    // year: $("#year")[0].checked,
                    // more_year: $("#more_year")[0].checked,

                    // custom: $("#custom")[0].checked,
                    // work_date_start: work_date_start,
                    // work_date_end: work_date_end,

                    page: page
                }
            }
            $.ajax({
                url: "{{ route('search_matter') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                data: data,
                success: function (response) {
                    $("#matters_body").html(response);
                }
            });
        }

        function reset() {
            page = 1;
            $("#keyword").val('');
            $('input[type=checkbox]:checked').prop('checked', false);
            handle_custom();
            search_matter();
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
            search_matter('paginate');

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
