<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous"> -->
    <title>Email Verification</title>

    <style>
        .img-header-atas {
            max-height: 100px;
        }

        .text-title {
            font-size: 30px;
            font-weight: bold;
            color: #9e9e9e;
        }

        .text-top {
            color: #696969;
        }

        .amount {
            font-size: 36px;
            font-weight: bold;
            color: #696969;
        }

        .text-bottom {
            color: #696969;
        }

        .bg-text-bottom {
            background-color: #e8e8e8;
        }

        .bg-biru {
            background-color: #0f4aad;
        }

        .text-pudar {
            color: #dedede;
        }

        .root {
            text-align: center;
        }

        .text-white {
            color: #ffffff;
        }

        .mt-4 {
            margin-top: 50px;
        }

        .p-4 {
            padding: 30px;
        }

        .mt-2 {
            margin-top: 20px;
        }

        .font-weight-bold {
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div>
        <div class="container root">
            <div class="row justify-content-center">
                <div class="col text-center p-4">
                    <img src="https://api.urunmandiri.com/logo.png" class="img-fluid img-header-atas" alt="">
                </div>
            </div>
            <hr>

            <div class="row justify-content-center">
                <div class="col text-center p-4">
                    <div class="text-title">
                        {{$title}}
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col text-center p-4">
                    <div class="text-title">
                        {{$invoice_number}}
                    </div>
                </div>
            </div>

            @if($status == 'pending')
            <div class="row justify-content-center">
                <div class="col text-center p-4">
                    <div class="text-top">
                        {!! $text_top !!}
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col text-center p-4">
                    <div class="amount">
                        Rp. {{number_format($nominal)}}
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col text-center p-4">
                    <div class="text-top">
                        melalui Virtual Account {{$bank}}
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col text-center p-4">
                    <div class="amount">
                        {{$va_number}}
                    </div>
                </div>
            </div>
            @else
            <div class="row justify-content-center">
                <div class="col text-center p-4">
                    <div style="font-size: 28px" class="text-bottom">
                        Terima Kasih!
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col text-center p-4">
                    <div class="text-top">
                        {!! $text_top_success !!}
                    </div>
                </div>
            </div>

            @endif

            <div class="row justify-content-center mt-4">
                <div class="col text-center p-4 bg-text-bottom">
                    <div class="text-top">
                        {{$text_bottom}}
                    </div>
                </div>
            </div>

            <div class="row justify-content-center mt-4">
                <div class="col text-center p-4 bg-text-bottom bg-biru">
                    <div class="row">
                        <div class="col font-weight-bold text-white">Copyright &copy; Urun Mandiri <?= date('Y') ?> </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col">
                            <div class="font-weight-bold text-white">Office</div>
                            <div class="text-pudar">Jl. Kabupaten No. 2, Kronggahan Gamping Sleman Yogyakarta</div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col">
                            <div class="font-weight-bold text-white">Telp</div>
                            <div class="text-pudar">0274-123456</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script> -->

</body>

</html>
