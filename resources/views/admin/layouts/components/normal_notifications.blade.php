<div class="alert alert-danger alert-dismissable error-block
    @if (!session('error')) display-none @endif">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    @if (session('error')) {{ session('error') }} @endif
</div>
<div class="alert alert-success alert-dismissable success-block
        @if (!session('success')) display-none @endif">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    @if (session('success')) {{ session('success') }} @endif
</div>
<div class="alert alert-warning alert-dismissable warning-block
        @if (!session('warning')) display-none @endif">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    @if (session('warning')) {{ session('warning') }} @endif
</div>
