<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('bahan-login/style.css') }}">
</head>

<body>


    <style>
        input[type="text"] {
            font-size: 12px !important;
        }

        input[type="password"] {
            font-size: 12px !important;
        }

        span {
            margin-bottom: 5px;
            display: inline-block;
            color: #607d8b;
            font-weight: 300;
            font-size: 12px;
            letter-spacing: 1px;
        }
    </style>

    <section>
        <div class="imgBx">
            <img src="{{ asset('bahan-login/assets/img/logo-login.jpg') }}" alt="">
        </div>


        <div class="contentBx">




            <div class="formBx">


                <p>
                    Silahkan login dengan akun anda..
                </p>


                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    <div class="inputBx">
                        <span>Email:</span>
                        <input type="text" name="email" placeholder="Masukan email anda..">
                    </div>


                    <div class="inputBx">
                        <span>Password:</span>
                        <input type="password" name="password" placeholder="Masukan password anda..">
                    </div>

                    <div class="inputBx">
                        <button type="submit">
                            Login
                        </button>
                    </div>

                    <a style="font-size: 12px;" href="{{ route('register') }}">Sudah punya akun? Silahkan daftar...</a>

                </form>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"
        integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous">
    </script>
</body>

</html>
