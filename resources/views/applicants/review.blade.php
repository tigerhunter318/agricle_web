@extends('layouts.dashboard')

@section('links')
    @include('css.links')
@endsection

@section('content')
    <div class="mb-4">
        <h3>{{__('messages.applicants.set_review')}}</h3>
    </div>
    <x-matter :recruitment="$recruitment" />

    <div class="row flex-row">
        <div class="col-lg-12">
            <button type="button" class="btn btn-warning mb-2" data-bs-toggle="modal" data-bs-target="#templateModal">
                {{__('messages.template.title')}}
            </button>
            <div class="modal fade" id="templateModal" tabindex="-1" role="dialog" aria-labelledby="contractModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="contractModalLabel">{{__('messages.template.title')}}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" id="template_modal_body">
                            @unless(count($templates) > 0)
                                <p class="text-center" id="empty_template"> {{__('messages.template.no_template')}} </p>
                            @endunless
                            @if(count($templates) > 0)
                                @foreach($templates as $template)
                                    <div class="row template_row" id="template_row{{$template['id']}}">
                                        <div class="row">
                                            <div class="col-lg-9 col-md-6 col-sm-12">
                                                <i class="fa fa-caret-right"></i>
                                                {{ $template['content'] }}
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-sm-12 text-center">
                                                <button type="button" class="btn btn-success btn-sm mb-2" data-bs-dismiss="modal" aria-label="Close" onclick="set_evaluation('{{ $template['content'] }}')">
                                                    <i class="fa fa-check"></i> {{__('messages.action.select')}}
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm mb-2" onclick="delete_template('{{ $template['id'] }}')">
                                                    <i class="fa fa-close"></i> {{__('messages.action.delete')}}
                                                </button>
                                            </div>
                                        </div>
                                        <hr class="horizontal dark mb-2" style="height: 2px;"/>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-success mb-2" onclick="save_template()" id="add_template_btn">
                <i class="fa fa-plus"></i> {{__('messages.template.add')}}
            </button>
            <form action="{{ route('finish_matter', ['matter_id' => $recruitment['id']]) }}" method="POST" id="review_form">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" id="status">
                <div class="input-group input-group-outline mb-2">
                    <textarea placeholder="{{__('messages.applicants.evaluation')}}" name="recruitment_evaluation" id="recruitment_evaluation" class="form-control" rows="10">{{ $applicant['recruitment_evaluation'] }}</textarea>
                </div>
                @error('recruitment_evaluation')
                <small class="text-danger">{{ $message }}</small>
                <div class="mb-2"></div>
                @enderror
                <input id="recruitment_review" name="recruitment_review" value="{{ $applicant['recruitment_review'] }}" type="text" class="rating" data-min=0 data-max=5 data-step=1 data-size="sm">
                <div class="mt-2">
                    @if($recruitment['status'] == 'working' && $applicant['status'] == 'approved')
                        <button type="button" class="btn btn-primary" onclick="set_review('abandoned')"> <i class="fa fa-close"></i>
                            {{__('messages.action.cancel')}} </button>
                    @elseif($recruitment['status'] == 'completed' || $applicant['status'] == 'finished')
                        <button type="button" class="btn btn-info" onclick="set_review('finished')"> <i class="fa fa-pencil"></i>
                            {{__('messages.action.evaluate')}} </button>
                    @elseif($applicant['status'] == 'fired')
                        <button type="button" class="btn btn-info" onclick="set_review('fired')"> <i class="fa fa-pencil"></i>
                            {{__('messages.action.evaluate')}} </button>
                    @endif
                    <button type="button" class="btn btn-secondary" onclick="javascript:history.go(-1);"> <i class="fa fa-arrow-left"></i>
                        {{__('messages.action.back')}} </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    @include('scripts.scripts')
    <script>
        var selected = 0;

        function set_evaluation(template) {
            $("#recruitment_evaluation").val(template);
        }

        function save_template() {
            if(!$("#recruitment_evaluation").val()) {
                swal({
                    title: "{{__('messages.alert.warning')}}",
                    text: "{{__('messages.template.no_template')}}",
                    type: 'warning',
                    confirmButtonText: "{{__('messages.action.yes')}}",
                });
                return;
            }
            swal({
                title: "{{__('messages.alert.confirm')}}",
                text: "{{__('messages.template.are_you_sure_to_add')}}",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: "{{__('messages.action.yes')}}",
                cancelButtonText: "{{__('messages.action.no')}}",
            }, function () {
                var url = "{{ route('review_templates.store') }}";
                var formdata = new FormData();
                formdata.append('content', $("#recruitment_evaluation").val());
                formdata.append('user_id', {{ Auth::user()->id }})

                $.ajax({
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    data: formdata,
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (response) {
                        $.toast({
                            heading: '{{__('messages.alert.success')}}',
                            text: '{{__('messages.alert.done_success')}}',
                            position: 'top-right',
                            icon: 'success'
                        });
                        var newItem = '' +
                            '<div class="row" id="template_row' + response['id'] + '">\
                                <div class="row">\
                                    <div class="col-lg-9 col-md-6 col-sm-12">\
                                        <i class="fa fa-caret-right"></i>\
                                        '+response['content']+'\
                                    </div>\
                                    <div class="col-lg-3 col-md-6 col-sm-12 text-center">\
                                        <button type="button" class="btn btn-success btn-sm mb-2" data-bs-dismiss="modal" aria-label="Close" onclick="set_evaluation('+response['content']+')">\
                                            <i class="fa fa-check"></i> {{__("messages.action.select")}}\
                                        </button>\
                                        <button type="button" class="btn btn-danger btn-sm mb-2" onclick="delete_template('+response['id']+')">\
                                            <i class="fa fa-close"></i> {{__("messages.action.delete")}}\
                                        </button>\
                                    </div>\
                                </div>\
                                 <hr class="horizontal dark mb-2" style="height: 2px;"/>\
                            </div>';
                        $("#empty_template").remove();
                        $("#template_modal_body").append(newItem);
                    },
                    error: function (response) {
                        $.toast({
                            heading: '{{__('messages.alert.error')}}',
                            text: '{{__('messages.alert.done_error')}}',
                            position: 'top-right',
                            icon: 'error'
                        })
                        console.log('error', response);
                    }
                });
            });
        }

        function set_review(status) {
            $("#status").val(status);
            $("#review_form").submit();
        }

        function delete_template(id) {
            swal({
                title: "{{__('messages.alert.confirm')}}",
                text: "{{__('messages.template.are_you_sure_to_delete')}}",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: "{{__('messages.action.yes')}}",
                cancelButtonText: "{{__('messages.action.no')}}",
            }, function () {
                var url = "{{ route('delete_template') }}";
                $.ajax({
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'DELETE',
                    data: {
                        id: id
                    },
                    success: function (response) {
                        $.toast({
                            heading: '{{__('messages.alert.success')}}',
                            text: '{{__('messages.alert.done_success')}}',
                            position: 'top-right',
                            icon: 'success'
                        })
                        $("#template_row"+id).remove();
                        if($('.template_row').length === 0) $('#template_modal_body').append('<p class="text-center" id="empty_template"> {{__("messages.template.no_template")}} </p>')
                    },
                    error: function (response) {
                        $.toast({
                            heading: '{{__('messages.alert.error')}}',
                            text: '{{__('messages.alert.done_error')}}',
                            position: 'top-right',
                            icon: 'error'
                        })
                        console.log(response);
                    }
                });
            });

        }
    </script>
@endsection
