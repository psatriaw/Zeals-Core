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
          <p>Hai {{ $first_name }}, <br> Mohon maaf kami tidak dapat menerima permohonan kemitraan anda dengan kode #<?=$mitra_code?> dikarenakan posisi pengajuan bersinggungan dengan posisi mitra kami lainnya yang sudah terdaftar.</p>
          <p>Anda tetap dapat melakukan pengajuan ulang dengan posisi lain. Terimakasih..</p>
      </div>
    </div>
  </body>
</html>
