@extends('layouts.adminDashboard')

@section('links')
    @include('css.adminLinks')
@endsection

@section('content')
    <section class="content-header pb-0">
        <h1>{{__('messages.sidebar.user_management')}}</h1>
    </section>

    <section class="content">
        <div style="display: flex; justify-content: end">
            <div class="form-group w-25">
                <label for="exampleSelectBorder">{{__('messages.role.title')}}</label>
                <select class="custom-select form-control-border" id="roleSelect">
                    <option value="*" {{ $role ==  '*' ? 'selected' : '' }}>{{__('messages.messages.all')}}</option>
                    <option value="producer" {{ $role ==  'producer' ? 'selected' : '' }}>{{__('messages.role.producer')}}</option>
                    <option value="worker" {{ $role ==  'worker' ? 'selected' : '' }}>{{__('messages.role.worker')}}</option>
                </select>
            </div>
            <div class="form-group w-25 ml-3">
                <label for="exampleSelectBorder">{{__('messages.profile.approved')}}</label>
                <select class="custom-select form-control-border" id="approveSelect">
                    <option value="*" {{ $approved == '*' ? 'selected' : '' }}>{{__('messages.messages.all')}}</option>
                    <option value="{{1}}" {{ $approved == '1' ? 'selected' : '' }}>{{__('messages.action.yes')}}</option>
                    <option value="{{0}}" {{ $approved == '0' ? 'selected' : '' }}>{{__('messages.action.no')}}</option>
                </select>
            </div>
        </div>
        <table class="table table-striped projects">
            <thead>
            <tr>
                <th style="width: 1%">
                </th>
                <th style="width: 20%">
                    Email
                </th>
                <th>
                    {{__('messages.profile.name')}}
                </th>
                <th style="text-align: center">
                    {{__('messages.role.title')}}
                </th>
                <th style="text-align: center">
                    {{__('messages.profile.approved')}}
                </th>
                <th>
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td>
                        <img src="{{ $user->avatar === 'default.png' ? asset('assets/img/utils/default.png') : asset('avatars/'.$user->avatar) }}" class="table-avatar" alt="User Image">
                    </td>
                    <td>
                        <a>
                            {{ $user->email }}
                        </a>
                        <br/>
                        <small>
                            登録日 {{ $user->created_at }}
                        </small>
                    </td>
                    <td>
                        {{ $user->name }}
                    </td>
                    <td align="center">
                        @if($user->role == 'producer')
                            <span class="badge badge-success">
                                {{__('messages.role.producer')}}
                            </span>
                        @else
                            <span class="badge badge-info">
                                {{__('messages.role.worker')}}
                            </span>
                        @endif
                    </td>
                    <td>
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" id="approveCheckbox{{$user->id}}" {{$user->approved ? 'checked' : ''}}>
                            <label for="approveCheckbox{{$user->id}}" class="custom-control-label">{{!!$user->approved ? '承認済み' : '未承認'}}</label>
                        </div>
                    </td>
                    <td class="project-actions text-right">
                        <a class="btn btn-primary btn-sm" href="{{route('view_user_detail', ['id' => $user->id])}}">
                            <i class="fas fa-eye">
                            </i>
                            {{__('messages.action.detail')}}
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="text-center">
            {{ $users->links('vendor.pagination.custom') }}
        </div>
    </section>

    <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
        <i class="fas fa-chevron-up"></i>
    </a>
@endsection

@section('scripts')
    @include('scripts.adminScripts')
    <script>
        $('[type="checkbox"]').on('change', function(){
            $.ajax({
                url: "{{route('set_user_approve')}}",
                type: 'post',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: this.id.replace('approveCheckbox', ''),
                    approved: this.checked?1:0
                },
                success: function(result) {
                    if(!!result) toastr.success(`{{__('messages.alert.done_success')}}`)
                }
            })
        })

        $('#approveSelect, #roleSelect').change(function () {
            const role = $('#roleSelect').val();
            const approved = $('#approveSelect').val();
            location.href = "{{url('admin/users/list')}}"+"/"+role+"/"+approved;
        })
    </script>
@endsection
