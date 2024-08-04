<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sagalotto dashboard</title>
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    @notifyCss
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="shortcut icon" href="assets/images/favicon.ico" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/css/bootstrap-select.min.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/1.1.2/css/bootstrap-multiselect.min.css"
        integrity="sha512-fZNmykQ6RlCyzGl9he+ScLrlU0LWeaR6MO/Kq9lelfXOw54O63gizFMSD5fVgZvU1YfDIc6mxom5n60qJ1nCrQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/css/bootstrap-grid.min.css"
        integrity="sha512-Aa+z1qgIG+Hv4H2W3EMl3btnnwTQRA47ZiSecYSkWavHUkBF2aPOIIvlvjLCsjapW1IfsGrEO3FU693ReouVTA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Public+Sans:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        html, body {
            font-family: "Lato", sans-serif;
            font-weight: 400;
            font-style: normal;
        }


        .containerr {
            display: flex;
            align-items: center;
            background: #fff;
            margin-top: 0px;
            margin-bottom: -20px;
            width: 100%;
        }

       

        .scroll-left span {
            clip-path: polygon(10% 0, 100% 0, 100% 100%, 0 100%);
        }
    </style>
</head>

<body>

    <div class="container-scroller">
        <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">

            <div class="containerr scroll-right">
                <marquee behavior="" direction="left">kounya ou ka kreye yon branch pouw mete vande ou vle parametre avek tarif spesifik</marquee>
            </div>
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">


                <a class="navbar-brand brand-logo" href="admin"><img src="assets/images/logo1.png"
                        alt="logo" /></a>
                <a class="navbar-brand brand-logo-mini" href="admin"><img src="assets/images/logo-mini.png"
                        alt="logo" /></a>

            </div>

            <div class="navbar-menu-wrapper d-flex align-items-stretch">
                <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                    <span class="mdi mdi-menu"></span>
                </button>

                <ul class="navbar-nav navbar-nav-right">
                    <li class="nav-item nav-profile dropdown">
                        <a class="nav-link dropdown-toggle" id="profileDropdown" href="#"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="nav-profile-img">
                                <img src="{{ session('logo') }}" alt="image">
                                <span class="availability-status online"></span>
                            </div>
                            <div class="nav-profile-text">
                                <p class="mb-1 text-black">{{ session('name') }}</p>
                            </div>
                        </a>
                        <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">

                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="/logout">
                                <i class="mdi mdi-logout me-2 text-primary"></i>Dekonekte</a>
                        </div>
                    </li>
                    <li class="nav-item nav-profile dropdown">

                        <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">

                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="/logout">
                                <i class="mdi mdi-logout me-2 text-primary"></i> Dekonekte </a>
                        </div>
                    </li>
                    <li class="nav-item d-none d-lg-block full-screen-link">
                        <a class="nav-link">
                            <i class="mdi mdi-fullscreen" id="fullscreen-button"></i>
                        </a>
                    </li>
                    <li class="nav-item dropdown">




                    </li>
                    <li class="nav-item dropdown">

                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                            aria-labelledby="notificationDropdown">
                            <h6 class="p-3 mb-0">Notifications</h6>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-success">
                                        <i class="mdi mdi-calendar"></i>
                                    </div>
                                </div>
                                <div
                                    class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                                    <h6 class="preview-subject font-weight-normal mb-1">Event today</h6>
                                    <p class="text-gray ellipsis mb-0"> Just a reminder that you have an event today
                                    </p>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-warning">
                                        <i class="mdi mdi-settings"></i>
                                    </div>
                                </div>
                                <div
                                    class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                                    <h6 class="preview-subject font-weight-normal mb-1">Settings</h6>
                                    <p class="text-gray ellipsis mb-0"> Update dashboard </p>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-info">
                                        <i class="mdi mdi-link-variant"></i>
                                    </div>
                                </div>
                                <div
                                    class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                                    <h6 class="preview-subject font-weight-normal mb-1">Launch Admin</h6>
                                    <p class="text-gray ellipsis mb-0"> New admin wow! </p>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <h6 class="p-3 mb-0 text-center">See all notifications</h6>
                        </div>
                    </li>
                    <li class="nav-item nav-logout d-none d-lg-block">
                        <a class="nav-link" href="/logout">
                            <i class="mdi mdi-power"></i>
                        </a>
                    </li>

                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                    data-toggle="offcanvas">
                    <span class="mdi mdi-menu"></span>
                </button>
            </div>
        </nav>

        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_sidebar.html -->
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                    <li class="nav-item nav-profile">
                        <a href="#" class="nav-link">
                            <div class="nav-profile-image">
                                <img src="{{ session('logo') }}" alt="profile">
                                <span class="login-status online"></span>
                                <!--change to offline or busy as needed-->
                            </div>
                            <div class="nav-profile-text d-flex flex-column">
                                <span class="font-weight-bold mb-2">{{ session('name') }}</span>
                                <span class="text-secondary text-small">Manager</span>
                            </div>
                            <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
                        </a>
                    </li>
                    <li class="nav-item">

                        <a class="nav-link" href="admin">
                            <span class="menu-title">Akèy</span>
                            <i class="mdi mdi-home menu-icon"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#vendeur" aria-expanded="false"
                            aria-controls="ui-basic">
                            <span class="menu-title">Vandè</span>
                            <i class="menu-arrow"></i>
                            <i class="mdi mdi-account menu-icon"></i>
                        </a>
                        <div class="collapse" id="vendeur">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item"> <a class="nav-link" href="ajouter-vendeur">Ajoute vandè</a>
                                </li>
                                <li class="nav-item"> <a class="nav-link" href="lister-vendeur">Lis vandè</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#tirage" aria-expanded="false"
                            aria-controls="ui-basic">
                            <span class="menu-title">Tiraj</span>
                            <i class="menu-arrow"></i>
                            <i class="mdi mdi-timetable menu-icon"></i>
                        </a>
                        <div class="collapse" id="tirage">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item"> <a class="nav-link" href="ajouter-tirage">Ajoute Tiraj</a></li>
                                <li class="nav-item"> <a class="nav-link" href="lister-tirage">Lis Tiraj</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#lo" aria-expanded="false"
                            aria-controls="ui-basic">
                            <span class="menu-title">Lo ki soti</span>
                            <i class="menu-arrow"></i>
                            <i class="mdi mdi-alarm menu-icon"></i>
                        </a>
                        <div class="collapse" id="lo">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item"> <a class="nav-link" href="/ajout-lo">Ajoute lo
                                    </a></li>
                                <li class="nav-item"> <a class="nav-link" href="lister-lo">Liste lo</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#autorisation" aria-expanded="false"
                            aria-controls="ui-basic">
                            <span class="menu-title">Bloke - limite pri</span>
                            <i class="menu-arrow"></i>
                            <i class="mdi mdi-earth menu-icon"></i>
                        </a>
                        <div class="collapse" id="autorisation">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item"> <a class="nav-link" href="/block">Bloke/Debloke
                                    </a></li>
                                <li class="nav-item"> <a class="nav-link" href="{{ route('limitprix') }}">Ajiste
                                        Limit Prix Achat
                                    </a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#rapo" aria-expanded="false"
                            aria-controls="ui-basic">
                            <span class="menu-title">Rapo</span>
                            <i class="menu-arrow"></i>
                            <i class="mdi mdi-chart-line menu-icon"></i>
                        </a>
                        <div class="collapse" id="rapo">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item"> <a class="nav-link" href="/rapport">Rapo general
                                    </a></li>
                                <li class="nav-item"> <a class="nav-link" href="/raport2">Rapo/vande
                                    </a></li>
                            </ul>
                        </div>

                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#statistique" aria-expanded="false"
                            aria-controls="ui-basic">
                            <span class="menu-title">Statistik</span>
                            <i class="mdi mdi-bell-plus" style="color:orange;margin-left:10px;"></i>
                            <i class="menu-arrow"></i>

                            <i class="mdi mdi-chart-line menu-icon"></i>
                        </a>
                        <div class="collapse" id="statistique">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item"> <a class="nav-link" href="/stat">statistiqueUnique
                                    </a></li>
                                <li class="nav-item"> <a class="nav-link"
                                        href="{{ route('statistique') }}">statistique General
                                    </a></li>

                            </ul>
                        </div>

                    </li>


                   
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#fich" aria-expanded="false"
                            aria-controls="ui-basic">
                            <span class="menu-title">Fich</span>
                            <i class="menu-arrow"></i>
                            <i class="mdi mdi-ticket menu-icon"></i>
                        </a>
                        <div class="collapse" id="fich">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item"> <a class="nav-link" href="lister-ticket">Chache
                                    </a></li>

                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#fich" aria-expanded="false"
                            aria-controls="ui-basic">
                            <span class="menu-title">Branch</span>
                            <i class="menu-arrow"></i>
                            <i class="mdi mdi-source-branch"></i>
                        </a>
                        <div class="collapse" id="fich">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item"> <a class="nav-link" href="creer_branch">Ajoute branch
                                    </a></li>
                                    <li class="nav-item"> <a class="nav-link" href="lister_branch">Liste branch
                                    </a></li>

                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#paramet" aria-expanded="false"
                            aria-controls="ui-basic">
                            <span class="menu-title">Paramet</span>
                            <i class="menu-arrow"></i>
                            <i class="mdi mdi-settings-box menu-icon"></i>
                        </a>
                        <div class="collapse" id="paramet">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item"> <a class="nav-link" href="{{ route('maryajGratis') }}">Maryaj
                                        Gratis
                                    </a></li>
                                <li class="nav-item"> <a class="nav-link" href="{{ route('ajisteprilo') }}">Ajiste
                                        pri premye lo
                                    </a></li>

                                <li class="nav-item"> <a class="nav-link" href="{{ route('lotconfig') }}">Lot
                                        Konfigirasyon
                                    </a></li>
                                <li class="nav-item"> <a class="nav-link" href="/plan">Compte & Plan
                                    </a></li>

                            </ul>
                        </div>

                    </li>

                </ul>

            </nav>
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">

                    @yield('content')
                </div>
                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
                <footer class="footer">
                    <div class="container-fluid d-flex justify-content-between">
                        <span class="text-muted d-block text-center text-sm-start d-sm-inline-block">Copyright ©
                            Sagalotto.com 2024</span>

                    </div>
                </footer>
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>

    <!-- container-scroller -->
    <!-- plugins:js -->

    <script src="assets/vendors/js/vendor.bundle.base.js"></script>



    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="assets/vendors/chart.js/Chart.min.js"></script>
    <script src="assets/js/jquery.cookie.js" type="text/javascript"></script>

    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="assets/js/off-canvas.js"></script>
    <script src="assets/js/hoverable-collapse.js"></script>
    <script src="assets/js/misc.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="assets/js/dashboard.js"></script>
    <script src="assets/js/todolist.js"></script>
    <script src="{{ asset('https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js') }}"></script>
    <script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js') }}"></script>
    <script
        src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/js/bootstrap-select.min.js') }}">
    </script>
    <!-- End custom js for this page -->
    <x-notify::notify />
    @notifyJs
    <script>
        $(document).ready(function() {
            $('.navbar-toggler').on('click', function() {
                $('.scroll-right').hide();
            });
        });
    </script>
</body>

</html>
