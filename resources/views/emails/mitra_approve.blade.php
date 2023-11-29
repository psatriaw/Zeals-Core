<html>
  <head>
    <style>
      .head{
        padding: 15px;
        font-size: 24px;
        background: #1cb394;
        color: #fff;
      }
      .btn-primary{
        padding: 15px;
        background-color: #1ab394;
        border-color: #1ab394;
        color: #FFFFFF !important;
        text-decoration: none;
        display: inline-block;
      }
      .footer{
        padding:10px;
        font-size: 13px;
        background: #f1f1f1;
      }
      .body{
        padding: 10px;
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
      }

      @media (max-width:480px){
        .head{
          font-size: 18px;
        }
      }
    </style>
  </head>
  <body>
    <div class="head">Response Permohonan Kemitraan</div>
    <div class="body">
      <div class='mg-b-25'>
          <p>Hai {{ $first_name }}, <br> Selamat, permintaan kemitraan dengan nama {{ $mitra_name }} milik anda dengan kode #<?=$mitra_code?> yang anda ajukan pada <?=date("d M Y H:i:s",$time_created) ?> melalui website kami nyatakan LAYAK untuk menjadi mitra kami. Mohon segera menghubungi admin kami di nomor <?=$phone?></p>
      </div>
    </div>
  </body>
</html>
