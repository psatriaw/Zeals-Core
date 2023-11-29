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
      New Campaign
    </div>
    <div class="body">
      <div class='mg-b-25'>Dear {{ $first_name }},<br>
        We hope this email finds you enthusiastic and ready to dive into new earning opportunities!
        We are thrilled to announce that our platform has just launched an array of fresh and exciting campaigns, perfectly tailored to suit your preferred category.
        <br>
        As a valued affiliate marketer on our platform,
        we understand the importance of matching you with campaigns that align with your interests,
        and that's why we're excited to present you with the latest additions that fall under your preferred category:
        <br>
        {{ $category }}
        <br>
        Here's how you can get started and and earn your extra income:
        <br>
        Step 1: Log in to your affiliate dashboard using your credentials.
        <br>
        Step 2: Explore the "Campaigns" section and filter by your preferred category.
        <br>
        Step 3: Choose the campaign(s) that ignite your excitement and align with your audience's interests.
        <br>
        Step 4: Grab your unique affiliate link for each campaign and start promoting them across your social channels, blogs, emails, and any other platforms you use.
        <br>
        These campaigns are in high demand and won't stay available forever. So, take action now and maximize your earnings. Happy promoting!
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
