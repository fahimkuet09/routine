<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title> @yield('title') </title>
    <meta content="Admin Dashboard" name="description" />
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{asset ('backend/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset ('backend/dist/css/adminlte.min.css') }}">
    @yield('stylesheets')
</head>

<body>

    <style>
        body {
            background: linear-gradient(135deg, #004080, #0073e6);
        }
    </style>

    <div class="container">
        <div class="container text-center">
            <!-- Company Logo -->
            <img src="{{ asset('ethnica_logo.png') }}" alt="Company Logo"
                style="max-width:120px; margin-top:30px;">

            <h4 class="text-center mt-4 text-light">Ethnica Routine Management System</h4>
        </div>
        <div class="row justify-content-center align-items-center" style="height: 80vh">
            <div class="col-md-8 pt-4">
                <div class="card bg-none">
                    <h4 class="text-center my-3">Select option</h4>
                    <div class="card-body">
                        <div class="form-group row mb-0">
                            <div class="col-md-6 mb-2">
                                <a class="w-100 btn btn-primary" href="#">
                                    View Routine
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a class="w-100 btn btn-danger" href="{{ route('login') }}">
                                    login
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- jQuery -->
    <script src="{{asset ('backend/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{asset ('backend/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{asset ('backend/dist/js/adminlte.min.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{asset ('backend/dist/js/demo.js') }}"></script>


</body>

</html>
