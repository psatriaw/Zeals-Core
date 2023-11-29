<html>
        <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
                <style>
                        .head{
                                padding: 15px;
                                font-size: 24px;
                                background: #f7f7f7;
                                color: #989898;
                                font-family: 'Open Sans', sans-serif;
                        }
                        .btn-primary{
                                padding: 15px;
                                background-color: #2f2f7c;
                                border-color: #2f2f7c;
                                color: #FFFFFF !important;
                                text-decoration: none;
                                display: inline-block;
                                font-family: 'Open Sans', sans-serif;
                        }
                        .footer{
                                line-height: 18px;
                                padding: 20px 15px 20px;
                                font-size: 15px;
                                background: #f1f1f1;
                                color: #888;
                                font-family: 'Open Sans', sans-serif;
                        }
                        .body{
                                padding: 10px 15px 30px;
                                background: #fafafa;
                                font-size:13px;
                        }
                        table{
                                border-collapse:collapse;
                        }
                        table td{
                                border:1px solid #ccc;
                                padding: 5px 10px;
                        }
                        .mg-b-25{
                                margin-bottom:25px;
                                margin-top: 25px;
                                font-family: 'Open Sans', sans-serif;
                                color: #444;
                                font-size: 18px;
                                line-height: 23px;
                        }

                        @media (max-width:480px){
                                .head{
                                        font-size: 18px;
                                }
                                div{
                                        text-align: center;
                                }
                        }
                        .primary-button {
                                display: inline-block;
                                padding: 10px 20px;
                                background-color: #2f307b;
                                color: #fff;
                                text-decoration: none;
                                border-radius: 5px;
                        }
                </style>
        </head>
        <body>
                <div class="head">
                        Password Reset
                </div>
                <div class="body">
                        <div class='mg-b-25'>Hi {{ $full_name }}, </div>
                        <div class='mg-b-25'>
                                We've create account for you as a Digital Brand Award Jury. Please change your password, and do not tell anyone. To reset your password, please click the following link.
                        </div>
                        <div class='mg-b-25' style="color: #fff">
                                <a href="https://zeals.asia/reset-password/{{ $user_id }}" class="primary-button">
                                        Reset Password
                                </a>
                        </div>
                </div>

                <div class="footer">
                        You are receiving this email because you are registered on Zeals Asia as a Jury
                        <br><br>
                        <small>
                                copyright &copy; Zeals Asia<br><br>
                                PT. Zeals Digital Asia<br>
                                Jln. Jalur Sutra Barat<br>
                                Tangerang - Banten 15143<br>
                        </small>
                </div>
        </body>
</html>
