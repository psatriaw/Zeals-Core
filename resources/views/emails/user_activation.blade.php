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
    </style>
  </head>
  <body>
    <div class="head">
      <img src="{{ url('templates/admin/img/logo.jpg') }}"><br>
      Account Activation
    </div>
    <div class="body">
      <div class='mg-b-25'>Dear {{ $first_name }},<br>
        Congratulations on taking the first step towards unlocking a world of possibilities!
        We're thrilled to have you join into Zeals Affiliate community.
        To get started, please confirm your email address by clicking the button below:
      </div>
      <div class=''>
        <a class="btn-primary" href="{{ @$activation_link }}">Activate Account</a>
      </div>
      <div class='mg-b-25'>
        Zeals Asia take data security seriously, so rest assured that your personal information is in safe hands.
        Our state-of-the-art security measures protect your data and privacy at all times.<br>
        Welcome aboard!
      </div>
    </div>

    <div class="footer">
      You are receiving this email because you are registered on Zeals Asia Platform. If this is not your email/account please confirm to our Adminsitrator through https://zeals.asia.
      <br><br>
        <small>
            copyright &copy; Zeals Asia<br><br>
            PT. Zeals Digital Asia<br>
            Jl. Jalur Sutera Bar. Alam No.15<br>
            Sutera, Kota Tangerang, Banten 15143<br>
        </small>
    </div>
  </body>
</html>
