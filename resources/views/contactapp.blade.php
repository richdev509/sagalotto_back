 <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="keywords" content="Sagacetech, SAGALOTO pos, POS haiti,">
    <meta name="description" content="SAGALOTO application POS bolot , yon pwodwi de Sagacetech">
    <meta name="author" content="Sagacetech">

    <title>SAGALOTO</title>

        <link rel="shortcut icon" href="{{ asset('/assets/landing/img/saga.png')}}" title="Favicon" >

        <!-- ====== bootstrap icons cdn ====== -->
        <link rel="stylesheet" href="ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" integrity="sha512-ZnR2wlLbSbr8/c9AgLg3jQPAattCUImNsae6NHYnS9KrIwRdcY9DxFotXhNAKIKbAXlRnujIqUWoXXwqyFOeIQ==" crossorigin="anonymous" referrerpolicy="no-referrer">

        <!-- bootstrap 5 -->
        <link rel="stylesheet" href="{{ asset('/assets/landing/css/lib/bootstrap.min.css')}}">

        <!-- ====== font family ====== -->
        <link href="css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="{{ asset('/assets/landing/css/lib/all.min.css')}}">
        <link rel="stylesheet" href="{{ asset('/assets/landing/css/lib/animate.css')}}">
        <link rel="stylesheet" href="{{ asset('/assets/landing/css/lib/jquery.fancybox.css')}}">
        <link rel="stylesheet" href="{{ asset('/assets/landing/css/lib/lity.css')}}">
        <link rel="stylesheet" href="{{ asset('/assets/landing/css/lib/swiper.min.css')}}">
        <!-- ====== global style ====== -->
        <link rel="stylesheet" href="{{ asset('/assets/landing/css/style.css')}}">
    </head>
    <body>

    <!-- ====== start loading page ====== -->
    <!-- <div id="preloader">
    </div> -->
    <!-- ---------- loader ---------- -->
    <div id="preloader">
        <div id="loading-wrapper" class="show">
            <div id="loading-text"> <img src="{{ asset('/assets/landing/img/saga.png')}}" alt=""> </div>
            <div id="loading-content"></div>
        </div>
    </div>
    <!-- ====== end loading page ====== -->

    <!-- ====== start top navbar ====== -->
    <div class="top-navbar style-4">
        <div class="container">
            <div class="content text-white">
                <span class="btn sm-butn bg-white py-0 px-2 me-2 fs-10px">
                    <small class="fs-10px">Spesyal</small>
                </span>
                <img src="{{ asset('/assets/landing/img/icons/nav_icon/imoj_heart.png')}}" alt="" class="icon-15">
                <span class="fs-10px op-6">Pran abonman paw la  </span>
                <small class="op-10 fs-10px">10$ mois</small>
                <span class="fs-10px op-6">jiska 1 Mai 2024</span>
                <a href="/contact" class="fs-10px text-decoration-underline ms-2">Pase komand</a>
            </div>
        </div>
    </div>
    <!-- ====== end top navbar ====== -->

    <!-- ====== start navbar ====== -->
   @include('navbar')
 <main class="contact-page style-5">
        <!-- ====== start contact page ====== -->
        <section class="community section-padding style-5">
            <div class="container">
                <div class="section-head text-center style-4 mb-40">
                    <small class="title_small">Kontakte nou</small>

                    <p>Nap kontaktew nan yon ti tan pa two lwen.</p>
                </div>
                <div class="content rounded-pill">
                    <div class="commun-card">
                        <div class="icon icon-45">
                            <img src="assets/img/icons/mail3d.png" alt="">
                        </div>
                        <div class="inf">
                            <h5>contact@sagaloto.com </h5>
                        </div>
                    </div>
                    <div class="commun-card">
                        <div class="icon icon-45">
                            <img src="assets/img/icons/map3d.png" alt="">
                        </div>
                        <div class="inf">
                            <h5>Petion-ville</h5>
                        </div>
                    </div>
                    <div class="commun-card">
                        <div class="icon icon-45">
                            <img src="assets/img/icons/msg3d.png" alt="">
                        </div>
                        <div class="inf">
                            <h5>+509 31-07-1890</h5>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- ====== end contact page ====== -->


    </main>
   @include('footer')
    <!-- ====== end footer ====== -->

    <!-- ====== start to top button ====== -->
    <a href="#" class="to_top bg-gray rounded-circle icon-40 d-inline-flex align-items-center justify-content-center">
        <i class="bi bi-chevron-up fs-6 text-dark"></i>
    </a>
    <!-- ====== end to top button ====== -->

    <!-- ====== request ====== -->
    <script src="{{ asset('/assets/landing/js/lib/jquery-3.0.0.min.js') }}"></script>
    <script src="{{ asset('/assets/landing/js/lib/jquery-migrate-3.0.0.min.js') }}"></script>
    <script src="{{ asset('/assets/landing/js/lib/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('/assets/landing/js/lib/wow.min.js') }}"></script>
    <script src="{{ asset('/assets/landing/js/lib/jquery.fancybox.js') }}"></script>
    <script src="{{ asset('/assets/landing/js/lib/lity.js') }}"></script>
    <script src="{{ asset('/assets/landing/js/lib/swiper.min.js') }}"></script>
    <script src="{{ asset('/assets/landing/js/lib/jquery.waypoints.min.js') }} "></script>
    <script src="{{ asset('/assets/landing/js/lib/jquery.counterup.js') }}"></script>
    <!--  <script src="{{ asset('/assets/landing/js/lib/pace.js') }}"></script> -->
    <script src="{{ asset('/assets/landing/js/lib/scrollIt.min.js') }}"></script>
    <script src="{{ asset('/assets/landing/js/main.js') }}"></script>
    </body>
</html>
