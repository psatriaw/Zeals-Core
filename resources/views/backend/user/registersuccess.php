<link href="{{ url('templates/admin/css/plugins/iCheck/custom.css') }}" rel="stylesheet">
<div class="middle-box text-center loginscreen   animated fadeInDown">
    <div>
        <div>
            <img src="<?=url("public/templates/admin/img/logo.png")?>" class="logo">
        </div>
        <h3>REGISTRATION SUCCESS</h3>
        <form class="m-t" role="form" action="{{ url('register-submit') }}" method="POST" id="registerForm">
            <p class="alert alert-success">Congratulation! You have been registered to CHAPONGIFT! Please check your email &amp; activate your account before login.</p>
            <p>If you can't find our email activation on your inbox, please check it on your spam folder</p>
        </form>

    </div>
</div>
