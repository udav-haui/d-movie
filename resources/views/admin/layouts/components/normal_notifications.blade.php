<div class="alert alert-danger alert-dismissable error-block"
    @if (!session('error')) style="display: none;" @endif>
    <button type="button" class="close" dmovie-noti-dismiss>×</button>
    @if (session('error')) {!! session('error') !!} @endif
</div>

<div class="alert alert-success alert-dismissable success-block"
     @if (!session('success')) style="display: none;" @endif >
    <button type="button" class="close" dmovie-noti-dismiss>×</button>
    @if (session('success')) {!! session('success') !!} @endif
</div>
<div class="alert alert-warning alert-dismissable warning-block
        @if (!session('warning')) display-none @endif">
    <button type="button" class="close" dmovie-noti-dismiss>×</button>
    @if (session('warning')) {!! session('warning') !!} @endif
</div>

{{--<div id="alerttopright" class="myadmin-alert myadmin-alert-img alert-info myadmin-alert-top-right">--}}
{{--    <img src="../plugins/images/users/genu.jpg" class="img" alt="img">--}}
{{--    <a href="#" class="closed">&times;</a>--}}
{{--    <h4>You have a Message!</h4>--}}
{{--    <b>John Doe</b> sent you a message.--}}
{{--</div>--}}

{{--<div class="alert alert-warning alert-dismissable warning-block">--}}
{{--    <button type="button" class="close" dmovie-noti-dismiss>×</button>--}}
{{--    test--}}
{{--</div>--}}
