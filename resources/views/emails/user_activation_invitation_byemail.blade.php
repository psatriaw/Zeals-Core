<html>
  <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <style>
      .banner-email{
        background: url("<?=url("uploads/banner_invitation_zeals.jpg")?>");
        background-size: cover;
        height: 380px !important;
        max-width: 800px;
      }
      .head{
        padding: 15px;
        font-size: 24px;
        background: #f7f7f7;
        color: #989898;
        font-family: 'Open Sans', sans-serif;
      }
      .btn-primary{
        font-size: 17px;
        padding: 20px 25px;
        background-color: #2b2e74;
        border-color: #ef7129;
        border: 3px solid #ef7129;
        color: #FFFFFF !important;
        text-decoration: none;
        display: inline-block;
        font-family: 'Open Sans', sans-serif;
        border-radius: 25px;
        font-weight: 500;
      }
      .footer{
        line-height: 25px;
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
        line-height: 27px;
      }
      ul{
        text-align: left;
        padding-left: 0px;
      }
      ul li{
        list-style: none;
        color:#777;
      }
      h1{
        line-height: 40px;
      }
      @media (max-width:480px){
        .head{
          font-size: 18px;
        }
        div{
          text-align: center;
        }
        .head img{
          height: 40px;
        }
        .banner-email{
          background: url("<?=url("uploads/banner_invitation_zeals.jpg")?>");
          background-size: cover;
          height: 180px !important;
        }
      }
    </style>
  </head>
  <body>
    <div class="head">
      <img src="{{ url('templates/admin/img/logo.jpg') }}"><br>
    </div>
    <div class="body">
      <div class='mg-b-25'>
        <h1>Hobi rebahan, tapi banyak mau-nya?</h1>
      </div>
      <div class="banner-email"></div>
      <div class='mg-b-25'>
        <h2>Halo Sobat Cuan! </h2>
­        <p>
          Hobi kamu rebahan, tapi banyak mau-nya?
          Kenalan sama Zeals Asia dulu dong!
          Di Zeals Asia, kamu bisa dapetin uang tanpa batas, dengan cuman share link doang loh!
        </p>
        <p>Gak cuman itu, jadi partner nya Zeals, kamu juga dapet keuntungan:</p>
        <ul>
          <li>✔️ Bisa dikerjain darimana aja</li>
          <li>✔️ Gak pake pengalaman kerja</li>
          <li>✔️ Bisa dapat komisi tinggi yang dapat dicairkan ke rekening langsung</li>
          <li>✔️ Voucher diskon atau potongan harga untuk diri sendiri</li>
        </ul>
        <p style="background: #f3ac3d;color: #fff;padding-top: 15px;padding-bottom: 15px;padding-left:15px;padding-right:15px;">
          Password kamu saat ini adalah: <strong><?=$real_password?></strong>
        </p>
        <p>
          Jadi, tunggu apa lagi?
          Yuk Aktivasi sekarang!
        </p>

        <div class=''>
          <a class="btn-primary" href="{{ @$activation_link }}">Activate Account</a>
        </div>
        <p>
          Butuh informasi lebih jelas? Join Telegram group Kita dibawah ini  ya!<br>
          <a href="https://t.me/+y22tdGkivs5hMTA1">https://t.me/+y22tdGkivs5hMTA1</a>
        </p>
      </div>
      <br><br>

    </div>

    <div class="footer">
      You are receiving this email because you are registered on Zeals Asia Platform. If this is not your email/account please confirm to our Adminsitrator through https://zeals.asia.
      <br><br>
        <small>
            copyright &copy; Zeals Asia<br><br>
            PT. Zeals Digital Asia<br>
            Jln. Tebet Barat 1 No. 2<br>
            Tebet - Jakarta Selatan 12810<br>
        </small>
    </div>
  </body>
</html>
