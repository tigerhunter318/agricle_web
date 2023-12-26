@extends('layouts.dashboard')

@section('links')
    @include('css.links')
@endsection

@section('content')
    <div class="mb-4">
        <h3>{{__('messages.title.applicant_detail')}}</h3>
    </div>
    <div class="row flex-row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h3>{{__('messages.applicants.applicant_list')}}</h3>
                    @unless(count($applicants) > 0)
                        <h4> {{__('messages.applicants.no_approved')}} </h4>
                    @endunless
                    @foreach($applicants as $applicant)
                        <a href="javascript:;" class="d-block p-2 border-radius-lg border-1 m-2 applicant-item" onclick="select_applicant({{$applicant['id']}}, this)">
                            <div class="d-flex p-2">
                                <img alt="Image" src="{{ $applicant['avatar'] === 'default.png' ? asset('assets/img/utils/default.png') : asset('avatars/'.$applicant['avatar']) }}" class="avatar">
                                <div class="ms-3">
                                    <h5 class="mb-1">{{ $applicant['name'] }}</h5>
                                    <p class="text-muted text-xs mb-2">
                                        <input id="worker_review{{$loop->index}}" readonly value="{{ $applicant['worker_review'] }}" type="text" class="rating" data-size="xs">
                                    </p>
                                    @if($applicant['status'] === 'waiting')
                                        <span class="text-warning">{{__('messages.applicants.status.waiting')}}</span>
                                    @elseif($applicant['status'] === 'approved')
                                        <span class="text-info">{{__('messages.applicants.status.approved')}}</span>
                                    @elseif($applicant['status'] === 'abandoned')
                                        <span class="text-dark">{{__('messages.applicants.status.abandoned')}}</span>
                                    @elseif($applicant['status'] === 'rejected')
                                        <span class="text-danger">{{__('messages.applicants.status.rejected')}}</span>
                                    @elseif($applicant['status'] === 'finished')
                                        <span class="text-success">{{__('messages.applicants.status.finished')}}</span>
                                        {{--                                    @elseif($applicant['status'] === 'fired')--}}
                                        {{--                                        <span class="text-primary">{{__('messages.applicants.status.fired')}}</span>--}}
                                    @else
                                        <span class="text-info"></span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-8">
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
                                                <button type="button" class="btn btn-success btn-sm mb-2" data-bs-dismiss="modal" aria-label="Close" onclick="set_evaluation({{ $template['id'] }})">
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
            <button type="button" class="btn btn-secondary" style="float:right;" onclick="javascript:history.go(-1);"> <i class="fa fa-arrow-left"></i>
                {{__('messages.action.back')}} </button>

            <div class="card text-center m-5" id="no_selected">
                <i class="fa fa-user" style="font-size: 60px"></i>
                <p class="text-center text-bold m-3"> {{__('messages.recruitment.select_applicant')}} </p>
            </div>

            <form action="{{ route('set_applicant_review', ['recruitment_id' => $recruitment->id]) }}" method="POST" id="review_form" style="display: none">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" id="status">
                <input type="hidden" name="applicant_id" id="applicant_id">
                <div class="input-group input-group-outline mb-2">
                    <textarea placeholder="{{__('messages.applicants.evaluation')}}" name="worker_evaluation" id="worker_evaluation" class="form-control" rows="10"></textarea>
                </div>
                @error('worker_evaluation')
                <small class="text-danger">{{ $message }}</small>
                <div class="mb-2"></div>
                @enderror
                <input id="worker_review" name="worker_review" value="0" type="text" class="rating" data-min=0 data-max=5 data-step=1 data-size="sm">
                <div class="mt-2" id="buttons">
                    <button type="button" class="btn btn-info" onclick="set_review('finished')"> {{__('messages.action.complete')}} </button>
                    <button type="button" class="btn btn-primary" onclick="set_review('fired')"> {{__('messages.action.stop')}} </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    @include('scripts.scripts')
    <script>
        var selected = 0;
        var applicants = @json($applicants);
        var templates = @json($templates);

        function set_evaluation(id) {
            var content =  templates.find(template => template.id === id).content;
            $("#worker_evaluation").val(content);
        }

        function select_applicant(applicant_id, ele) {
            $("#no_selected").remove();
            $("#review_form").removeAttr('style');
            var applicant = applicants.find(applicant => applicant.id === applicant_id);
            $("#applicant_id").val(applicant_id);
            $("#worker_evaluation").val(applicant['worker_evaluation']);
            $("#worker_review").rating('update', applicant['worker_review']);
            var items = $(document).find('.applicant-item');
            for(var i=0; i<items.length; i++) items[i].classList.remove('card');
            ele.classList.add('card');
        }

        function save_template() {
            if(!$("#worker_evaluation").val()) {
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
                formdata.append('content', $("#worker_evaluation").val());
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
                        templates.push(response);
                        $.toast({
                            heading: '{{__('messages.alert.success')}}',
                            text: '{{__('messages.alert.done_success')}}',
                            position: 'top-right',
                            icon: 'success'
                        })
                        var newItem = '' +
                            '<div class="row" id="template_row' + response['id'] + '">\
                                <div class="row">\
                                    <div class="col-lg-9 col-md-6 col-sm-12">\
                                        <i class="fa fa-caret-right"></i>\
                                        '+response['content']+'\
                                    </div>\
                                    <div class="col-lg-3 col-md-6 col-sm-12 text-center">\
                                        <button type="button" class="btn btn-success btn-sm mb-2" data-bs-dismiss="modal" aria-label="Close" onclick="set_evaluation('+response['id']+')">\
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
