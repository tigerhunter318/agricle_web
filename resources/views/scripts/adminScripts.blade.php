<script src="{{ asset('admin_assets/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('admin_assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('admin_assets/plugins/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('admin_assets/dist/js/adminlte.min.js') }}"></script>
<script src="{{asset('assets/plugins/star-rating/js/star-rating.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/plugins/star-rating/themes/krajee-svg/theme.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/plugins/star-rating/js/locales/ja.js')}}" type="text/javascript"></script>
<script>
    $('.rating').rating({
        'showClear': false,
        'showCaption': true,
        'language': 'ja',
        'theme': 'krajee-svg',
        starCaptions: function(val) {
            return val;
        }
    });
</script>
