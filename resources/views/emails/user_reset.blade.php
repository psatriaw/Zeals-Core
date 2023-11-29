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
        font-size: 18px;
        font-family: 'Open Sans', sans-serif;
        color: #444;
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
      Reset Password
    </div>
    <div class="body">
      <div class='mg-b-25'>Dear {{ $first_name }},<br>
        We hope this email finds you well.
        It appears that you've requested a password reset for your Zeals Affiliate account, and we're here to assist you in regaining access.
        To proceed with the password reset, please re-login to your account with the following password:<br>
        <strong>{{ @$password }}</strong><br>
        Once you have successfully logged in, you will have the option to modify your password in your Account settings at any time.<br>
        If you didn't initiate this request or believe this to be an error, please ignore this email.
        Rest assured that your account remains secure, and your security is of utmost importance to us.
        However, if you continue to receive such emails without any action from your side, please reach out to our support team immediately.<br>
        Thank you for being a valued member of our community!
      </div>
    </div>

    <div class="footer">
    You are receiving this email because you are registered on Zeals Asia Platform. If this is not your email/account please confirm to our Adminsitrator through https://zeals.asia.<br><br>
 
      <small>
        copyright &copy; Zeals Asia<br><br>
        PT. Zeals Digital Asia<br>
        Jl. Jalur Sutera Bar. Alam No.15<br>
        Sutera, Kota Tangerang, Banten 15143<br>
      </small>
    </div>
  </body>
</html>
