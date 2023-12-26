<div class="m-3">
    <div class="row">
        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4 text-center">
            <img
                src="{{ $applicant['avatar'] === 'default.png' ? asset('assets/img/utils/default.png') : asset('avatars/'.$applicant['avatar']) }}"
                class="img-thumbnail"
                data-bs-toggle="tooltip"
                data-bs-placement="bottom"
                title="{{ $applicant['appeal_point'] }}"
                data-container="body"
                data-animation="true"
            >
            <input value="{{ $applicant['review'] }}" type="text" class="rating" data-size="sm" readonly>

            {{--            <p>{{ $applicant['email'] }}</p>--}}
        </div>
        <div class="col-md-10 col-sm-6">
            <div class="row">
                <div class="col-md-2 col-sm-6">
                    <h6 class="font-weight-bolder d-flex align-items-center">
                        {{__('messages.profile.name')}}:
                    </h6>
                </div>
                <div class="col-md-2 col-sm-6">
                    <p>{{ $applicant['family_name'] }}</p>
                </div>
                <div class="col-md-2 col-sm-6">
                    <h6 class="font-weight-bolder d-flex align-items-center">
                        {{__('messages.profile.name_read')}}:
                    </h6>
                </div>
                <div class="col-md-2 col-sm-6">
                    <p>{{ $applicant['name'] }}</p>
                </div>
                <div class="col-md-2 col-sm-6">
                    <h6 class="font-weight-bolder d-flex align-items-center">
                        {{__('messages.profile.nickname')}}:
                    </h6>
                </div>
                <div class="col-md-2 col-sm-6">
                    <p>{{ $applicant['nickname'] }}</p>
                </div>
                <div class="col-md-2 col-sm-6">
                    <h6 class="font-weight-bolder d-flex align-items-center">
                        {{__('messages.profile.age')}}:
                    </h6>
                </div>
                <div class="col-md-2 col-sm-6">
                    <p>
                        {{ \Carbon\Carbon::parse($applicant['birthday'])->diff(\Carbon\Carbon::now())->y }}
                    </p>
                </div>
                <div class="col-md-2 col-sm-6">
                    <h6 class="font-weight-bolder d-flex align-items-center">
                        {{__('messages.profile.post_number')}}:
                    </h6>
                </div>
                <div class="col-md-2 col-sm-6">
                    <p>{{ $applicant['post_number'] }}</p>
                </div>
                <div class="col-md-2 col-sm-6">
                    <h6 class="font-weight-bolder d-flex align-items-center">
                        {{__('messages.profile.prefectures')}}:
                    </h6>
                </div>
                <div class="col-md-2 col-sm-6">
                    <p>{{ get_prefectures_name($applicant['prefectures']) }}</p>
                </div>
                <div class="col-md-2 col-sm-6">
                    <h6 class="font-weight-bolder d-flex align-items-center">
                        {{__('messages.profile.city')}}:
                    </h6>
                </div>
                <div class="col-md-2 col-sm-6">
                    <p>{{ $applicant['city'] }}</p>
                </div>
                <div class="col-md-2 col-sm-6">
                    <h6 class="font-weight-bolder d-flex align-items-center">
                        {{__('messages.profile.cell_phone')}}:
                    </h6>
                </div>
                <div class="col-md-2 col-sm-6">
                    <p>{{ $applicant['cell_phone'] }}</p>
                </div>
                <div class="col-md-2 col-sm-6">
                    <h6 class="font-weight-bolder d-flex align-items-center">
                        {{__('messages.profile.emergency_phone')}}:
                    </h6>
                </div>
                <div class="col-md-2 col-sm-6">
                    <p>{{ $applicant['emergency_phone'] }}</p>
                </div>
                <div class="col-md-2 col-sm-6">
                    <h6 class="font-weight-bolder d-flex align-items-center">
                        {{__('messages.profile.emergency_relation')}}:
                    </h6>
                </div>
                <div class="col-md-2 col-sm-6">
                    <p>{{ $applicant['emergency_relation'] }}</p>
                </div>
                <div class="col-md-2 col-sm-6">
                    <h6 class="font-weight-bolder d-flex align-items-center">
                        {{__('messages.profile.job')}}:
                    </h6>
                </div>
                <div class="col-md-2 col-sm-6">
                    <p>{{ $applicant['job'] }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2 col-sm-4">
                    <h6 class="font-weight-bolder d-flex align-items-center">
                        {{__('messages.profile.bio')}}:
                    </h6>
                </div>
                <div class="col-md-10 col-sm-8">
                    <p>{{ $applicant['bio'] }}</p>
                </div>
                <div class="col-md-2 col-sm-4">
                    <h6 class="font-weight-bolder d-flex align-items-center">
                        {{__('messages.profile.appeal_point')}}
                    </h6>
                </div>
                <div class="col-md-10 col-sm-8">
                    <p>{{ $applicant['appeal_point'] }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

