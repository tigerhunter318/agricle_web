<div class="m-3">
    <div class="row my-4 mb-0 mx-2">
        <div class="col-md-2 col-sm-6 text-center">
            <img src="{{ $producer['avatar'] === 'default.png' ? asset('assets/img/utils/default.png') : asset('avatars/'.$producer['avatar']) }}" class="img-thumbnail img-fluid me-2" style="width: 100%" />
            <input value="{{ $producer['review'] }}" type="text" class="rating" readonly data-size="xs">
        </div>
        <div class="col-md-10 col-sm-6">
            <div class="row">
                <div class="col-md-2 col-sm-6">
                    <h6 class="font-weight-bolder d-flex align-items-center">
                        {{__('messages.profile.producer_name')}}
                    </h6>
                </div>
                <div class="col-md-4 col-sm-6">
                    <p>{{ $producer['family_name'] }}</p>
                </div>
                <div class="col-md-2 col-sm-6">
                    <h6 class="font-weight-bolder d-flex align-items-center">
                        {{__('messages.profile.name_read')}}
                    </h6>
                </div>
                <div class="col-md-4 col-sm-6">
                    <p>{{ $producer['name'] }}</p>
                </div>
                <div class="col-md-2 col-sm-6">
                    <h6 class="font-weight-bolder d-flex align-items-center">
                        {{__('messages.profile.management_mode.title')}}
                    </h6>
                </div>
                <div class="col-md-4 col-sm-6">
                    <p>
                        @if($producer['management_mode'] === 'individual')
                            {{__('messages.profile.management_mode.individual')}}
                        @elseif($producer['management_mode'] === 'corporation')
                            {{__('messages.profile.management_mode.corporation')}}
                        @elseif($producer['management_mode'] === 'other')
                            {{__('messages.profile.management_mode.other')}}
                        @endif
                    </p>
                </div>
                <div class="col-md-2 col-sm-6">
                    <h6 class="font-weight-bolder d-flex align-items-center">
                        {{__('messages.profile.address')}}
                    </h6>
                </div>
                <div class="col-md-4 col-sm-6">
                    <p>{{ format_address($producer['post_number'], $producer['prefectures'], $producer['city'], $producer['address']) }}</p>
                </div>
                <div class="col-md-2 col-sm-6">
                    <h6 class="font-weight-bolder d-flex align-items-center">
                        {{__('messages.profile.insurance.title')}}
                    </h6>
                </div>
                <div class="col-md-4 col-sm-6">
                    <p>{{ $producer['insurance'] ? __('messages.profile.insurance.yes') : __('messages.profile.insurance.no') }}</p>
                </div>
                <div class="col-md-2 col-sm-6">
                    <h6 class="font-weight-bolder d-flex align-items-center">
                        {{__('messages.profile.other_insurance')}}
                    </h6>
                </div>
                <div class="col-md-4 col-sm-6">
                    <p>{{ $producer['other_insurance'] }}</p>
                </div>
                <div class="col-md-2 col-sm-6">
                    <h6 class="font-weight-bolder d-flex align-items-center">
                        {{__('messages.profile.product_name')}}
                    </h6>
                </div>
                <div class="col-md-4 col-sm-6">
                    <p>{{ $producer['product_name'] }}</p>
                </div>
                <div class="col-md-2 col-sm-6">
                    <h6 class="font-weight-bolder d-flex align-items-center">
                        {{__('messages.profile.appeal_point')}}
                    </h6>
                </div>
                <div class="col-md-4 col-sm-6">
                    <p>{{ $producer['appeal_point'] }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

