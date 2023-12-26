@extends('layouts.dashboard')

@section('links')
    @include('css.links')
@endsection

@section('content')
    <div class="mb-4">
        <h3>{{__('messages.title.matters_detail')}}</h3>
    </div>

    <x-matter :recruitment="$matter" />

    <div class="col-12 text-end">
        <button type="button" class="btn btn-success m-2" data-bs-toggle="modal" data-bs-target="#commentModal">
            <i class="fa fa-check"></i> {{__('messages.action.apply')}}
        </button>
        <button type="button" class="btn btn-secondary m-2" onclick="javascript:history.go(-1);">
            <i class="fa fa-arrow-left"></i> {{__('messages.action.back')}}
        </button>
    </div>
    <div class="modal fade" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="commentLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="commentLabel">{{__('messages.alert.are_you_sure_to_apply')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="comment_modal_body">
                    <form enctype='multipart/form-data' method="POST" action="{{ route('apply_matter', ['matter_id' => $matter['id']]) }}" id="form" autocomplete="off">
                        @csrf
                        <div class="input-group input-group-outline">
                            <textarea name="apply_memo" id="apply_memo" class="form-control" type="text" rows="5" placeholder="{{__('messages.applicants.apply_memo').__('messages.applicants.apply_memo_example')}}"></textarea>
                        </div>
                        <small class="text-danger" id="apply_memo_error" style="display: none"></small>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">キャンセル</button>
                    <button type="button" class="btn btn-success" onclick="apply()"> <i class="fa fa-check"></i> {{__('messages.action.apply')}}  </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    @include('scripts.scripts')
    <script>
        function apply() {
            $("#form").submit();
        }
    </script>
@endsection
