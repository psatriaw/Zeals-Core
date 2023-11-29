<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Demo Request</title>
    <style>
    .body{
        margin: auto!important;
        background-color: #FFF!important;
        padding: 10px!important;
        font-family: "Helvetica",sans-serif;
        max-width: 500px!important;
        color: #000!important;
        border: 1px solid #000!important;
    }
    .row{
        margin:0;
    }
    .content-center{
        text-align:center!important;
    }
    .header{
        padding:20px 0 20px 10px;
        min-height: 100px;
        text-align: left!important;
    }
    .logo{
        width: 171px!important;
        height: 72px!important;
    }
    .content{
        padding: 20px 30px!important;
        border: 1px solid #9f9f9f!important;
        box-shadow: 0px -1px 2px rgba(0, 0, 0, 0.04), 0px 4px 8px rgba(0, 0, 0, 0.08)!important;
        border-radius: 20px;
        color: #000!important;
    }
    .text-title{
        font-weight:800;
        font-size:30px;
        margin-bottom: 30px;
    }
    .text-greeting{
        font-weight:400;
        font-size:30px;
    }
    .text-greeting span{
        font-weight:800;
    }
    .text-contact{
        padding: 40px 0;
        font-size: 15px;
        line-height: 25px;
        text-align: left!important;
        color: #000!important;
    }
    .button{
        display:inline-block;
        background-color:#2F2F7C;
        color:#ffffff;
        border: 0px;
        border-radius: 8px;
        font-weight: 800;
        width: 200px;
        font-size:20px;
        text-align:center;
        text-decoration: none;
        padding: 10px;
    }
    .text-bottom{
        margin-top: 10px;
        padding: 15px;
        font-size: 11px;
        color: #9f9f9f;
        text-align: left!important;
    }
    </style>
</head>

<body>
    <div class="body">
        <div class="header">
            <div class="row">
                <img src="https://app.zeals.asia/templates/admin/img/logo.jpg" class="logo">
            </div>
        </div>
        <div class="content">
            <div class="row content-center">
                <div class="text-title">
                    {{$title}}
                </div>
            </div>
            <div class="row content-center">
                <div class="text-greeting">
                    Hi, <span>BD Team!</span><br>
                    Ada request Demo loh
                </div>
            </div>
            <div class="row">
                <div class="text-contact">
<!-- <pre> -->
Nama PIC    : {{$fname}}<br>
Perusahaan  : {{$cname}}<br>
Email       : {{$email}}<br>
No. Telp    : {{$phone}}<br>
Notes :<br><br>
{{$notes}}
<!-- </pre> -->
                </div>
            </div>
            <div class="row content-center">
                <a href="#" class="btn button">Call Them</a>
            </div>
        </div>
        <div class="text-bottom">
            You are receiving this email because you are registered on Zeals Asia Platform. If this is not your email/account please confirm to our Administrator through https://zeals.asia.
                <br><br>
            copyright &copy; Zeals Asia
                <br><br>
            PT. Zeals Digital Asia <br>
            Jln. Tebet Barat 1 No. 2 <br>
            Tebet - Jakarta Selatan 12810 <br>
        </div>
    </div>
</body>
</html>