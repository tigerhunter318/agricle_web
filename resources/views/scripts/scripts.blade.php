<script src="{{asset('assets/js/core/popper.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/core/bootstrap.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/core/jquery-3.6.0.min.js')}}" type="text/javascript"></script>

<script src="{{asset('assets/plugins/star-rating/js/star-rating.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/plugins/star-rating/themes/krajee-svg/theme.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/plugins/star-rating/js/locales/ja.js')}}" type="text/javascript"></script>

<script src="{{asset('assets/plugins/read-more-less-expander/jquery.expander.js')}}" type="text/javascript"></script>

<script type="text/javascript" src="{{asset('assets/plugins/daterangepicker/moment.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/plugins/daterangepicker/daterangepicker.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>

<script src="{{asset('assets/plugins/toast/js/jquery.toast.js')}}" type="text/javascript"></script>

<script src="{{asset('assets/plugins/multi-select/magicsuggest.js')}}"></script>

<script src="{{asset('assets/js/plugins/perfect-scrollbar.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/prism.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/highlight.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/parallax.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/flatpickr.min.js')}}"></script>
<script src="{{asset('assets/js/material-kit.min.js?v=3.0.2')}}" type="text/javascript"></script>

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

    var reminder = setInterval(myTimer, 1000);

    function myTimer() {
        const d = new Date();
        if(d.getHours() === 17) {
            $.ajax({
                url: "{{ route('reminder') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
            });
            clearInterval(reminder);
        }
    }
</script>
