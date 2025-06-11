<footer class="footer">
    <div class="d-sm-flex justify-content-center justify-content-sm-between">
        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright ©
            {{ date('Y') }} <a href="{{ route('user.dashboard') }}"
                target="_blank">{{ $settings->site_name ?? config('app.name') }}</a>. All
            rights
            reserved.</span>
    </div>
</footer>
