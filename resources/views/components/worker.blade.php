<div class="m-3">
    <div class="row">
        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4 text-center">
            <img
                src="{{ $worker['avatar'] === 'default.png' ? asset('assets/img/utils/default.png') : asset('avatars/'.$worker['avatar']) }}"
                class="avatar avatar-xl me-3"
                data-bs-toggle="tooltip"
                data-bs-placement="bottom"
                title="{{ $worker['appeal_point'] }}"
                data-container="body"
                data-animation="true"
            >
            <input value="{{ $worker['review'] }}" type="text" class="rating" readonly data-size="xs">
        </div>
        <div class="col-lg-10 col-md-9 col-sm-8">
            <div class="row">
                <div class="col-md-2 col-sm-6">
                    <h6 class="font-weight-bolder d-flex align-items-center">
                        {{__('messages.profile.nickname')}}:
                    </h6>
                </div>
                <div class="col-md-10 col-sm-6">
                    <p>{{ $worker['nickname'] }}</p>
                </div>
                <div class="col-md-2 col-sm-6">
                    <h6 class="font-weight-bolder d-flex align-items-center">
                        {{__('messages.profile.bio')}}:
                    </h6>
                </div>
                <div class="col-md-10 col-sm-6">
                    <p>{{ $worker['bio'] }}</p>
                </div>
                <div class="col-md-2 col-sm-4">
                    <h6 class="font-weight-bolder d-flex align-items-center">
                        {{__('messages.profile.appeal_point')}}
                    </h6>
                </div>
                <div class="col-md-10 col-sm-8">
                    <p>{{ $worker['appeal_point'] }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

