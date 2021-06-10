<?php use Jenssegers\Agent\Agent; $agent = new Agent();?>
<footer class="footer pt-0">
    <div class="row align-items-center justify-content-lg-between">
        <div class="col-lg-6">
            <div class="copyright text-center text-lg-left text-muted">
                @if($agent->isDesktop())
                    &copy; 2020 <a href="#" class="font-weight-bold ml-1">PT Pengelola Pusat Perdagangan Caringin</a>
                @else
                    &copy; 2020 <a href="#" class="font-weight-bold ml-1">PT P3C</a>
                @endif
            </div>
        </div>
        <div class="col-lg-6">
            <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                <li class="nav-item">
                    <a href="https://kapns.com" class="nav-link" target="_blank">Creative Team</a>
                </li>
                <li class="nav-item">
                    <a href="https://github.com/LevindSein" class="nav-link" target="_blank">License</a>
                </li>
                <li class="nav-item">
                    <a href="{{url('information')}}" class="nav-link">Version 2.3.0</a>
                </li>
            </ul>
        </div>
    </div>
</footer>