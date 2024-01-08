<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="keywords" content="Sagacetech, SAGALOTTO pos, POS haiti,">
    <meta name="description" content="SAGALOTTO application POS bolot , yon pwodwi de Sagacetech">
    <meta name="author" content="Sagacetech">
        
    <title>SAGALOTTO.</title>

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
    <div class="top-navbar style-1">
        <div class="container">
            <div class="content">
                <div class="row align-items-center">
                    
                    <div class="col-lg-4">
                        <div class="r-side">
                            <div class="socail-icons">
                                <a href="#" style="display:none;">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="https://facebook.com/sagacetech">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ====== end top navbar ====== -->

    <!-- ====== start navbar ====== -->
    <nav class="navbar navbar-expand-lg navbar-light style-1">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('/assets/landing/img/logo_cd.png')}}" alt="">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link active dropdown-toggle" href="#" id="navbarDropdown1" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Homes
                        </a>
                    
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="#service">
                            Services
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="page-blog-5.html">
                            blog
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="page-contact-5.html">
                            contact
                        </a>
                    </li>
                </ul>
                <div class="nav-side">
                    <div class="hotline pe-4">
                        <div class="icon me-3">
                            <i class="bi bi-telephone"></i>
                        </div>
                        <div class="cont">
                            <small class="text-muted m-0">hotline 24/7</small>
                            <h6>(+23) 5535 68 68</h6>
                        </div>
                    </div>
                    <div class="qoute-nav ps-4">
                        <a href="#" class="search-icon me-3">
                            <i class="bi bi-search"></i>
                        </a>
                        <a href="#" class="cart-icon me-3">
                            <i class="bi bi-cart"></i>
                            <span class="cart-num">
                                2
                            </span>
                        </a>
                        <a href="page-contact-5.html" class="btn sm-butn butn-gard border-0 text-white">
                            <span>Free Quote</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <!-- ====== end navbar ====== -->

    <!-- ====== start header ====== -->
    <header class="section-padding style-1">
        <div class="container">
            <div class="content">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="info">
                            <div class="section-head mb-60">
                                <h6 class="color-main text-uppercase">SagaceTech</h6>
                                <h2>
                                    SagaceTech <span class="fw-normal">Solutions</span>
                                </h2>
                            </div>
                            <div class="text">
                                We transform businesses of most major sectors with powerful and adaptable digital solutions that satisfy the needs of today.
                            </div>
                            <div class="bttns mt-5">
                                <a href="page-services-5.html" class="btn btn-dark">
                                    <span>our services</span>
                                </a>
                                <a href="https://youtu.be/pGbIOC83-So?t=21" data-lity="" class="vid-btn">
                                    <i class="bi bi-play wow heartBeat infinite slow"></i>
                                    <span>
                                       SAGACETECH <br> Showreels
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 offset-lg-1">
                        <div class="img">
                            <img src="{{ asset('/assets/landing/img/header/head.png')}}" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <img src="{{ asset('/assets/landing/img/header/head_shape_r.png')}}" alt="" class="head-shape-r wow">
        <img src="{{ asset('/assets/landing/img/header/head_shape_l.png')}}" alt="" class="head-shape-l wow">
    </header>
    <!-- ====== end header ====== -->

    <!--Contents-->
    <main>
        <section class="about style-1">
            <div class="container">
                <div class="content">
                    <div class="about-logos d-flex align-items-center justify-content-between border-bottom border-1 brd-light pb-20">
                        <a href="#" class="logo wow fadeInUp" data-wow-delay="0">
                            <img src="assets/img/about/about_logos/1.png" alt="">
                        </a>
                        <a href="#" class="logo wow fadeInUp" data-wow-delay="0.2s">
                            <img src="assets/img/about/about_logos/2.png" alt="">
                        </a>
                        <a href="#" class="logo wow fadeInUp" data-wow-delay="0.4s">
                            <img src="assets/img/about/about_logos/3.png" alt="">
                        </a>
                        <a href="#" class="logo wow fadeInUp" data-wow-delay="0.6s">
                            <img src="assets/img/about/about_logos/4.png" alt="">
                        </a>
                        <a href="#" class="logo wow fadeInUp" data-wow-delay="0.8s">
                            <img src="assets/img/about/about_logos/5.png" alt="">
                        </a>
                    </div>
                    <div class="about-info">
                        <div class="row justify-content-between">
                            <div class="col-lg-5">
                                <div class="title">
                                    <h3 class=" wow fadeInUp slow">“Technology is best when it brings people  together.”</h3>
                                    <small class=" wow fadeInUp slow fw-bold">Cereme Nathalie</small>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="info">
                                    <h6 class=" wow fadeInUp slow">We can help to maintain and modernize your IT infrastructure  & solve various infrastructure-specific issues a business may face.</h6>
                                    <p class=" wow fadeInUp slow">Sagacetech is the partner of choice for many of the world’s leading  enterprises, SMEs and technology challengers. We help businesses  elevate their value through custom software development, product  design, QA and consultancy services.</p>    
                                    <a href="page-about-5.html" class="btn btn-outline-light mt-5 sm-butn wow fadeInUp slow">
                                        <span>more about us</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                   
                    <img src="assets/img/about/num_shap.png" alt="" class="about_shap">
                </div>
            </div>
        </section>

        <section class="services section-padding style-1" id="service">
            <div class="container">
                <div class="row">
                    <div class="col offset-lg-1" >
                        <div class="section-head mb-60">
                            <h6 class="color-main text-uppercase wow fadeInUp">our services</h6>
                            <h2 class="wow fadeInUp">
                                Perfect IT Solutions <span class="fw-normal">For Your Business</span>
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="content">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="service-box mb-4 wow fadeInUp" data-wow-delay="0">
                                <h5>
                                    <a href="page-services-5.html"> IT Consultation </a> 
                                    <span class="num">01</span>
                                </h5>
                                <div class="icon">
                                    <img src="{{ asset('/assets/landing/img/icons/serv_icons/1.png')}}" alt="">
                                </div>
                                <div class="info">
                                    <div class="text">
                                        Trust our top minds to eliminate workflow pain points, implement new tech & app.
                                    </div>
                                    <div class="tags">
                                        <a href="#">Strategy</a>
                                        <a href="#">Consultation</a>
                                        <a href="#">Management</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="service-box mb-4 wow fadeInUp" data-wow-delay="0.2s">
                                <h5>
                                    <a href="page-services-5.html"> Data Security </a> 
                                    <span class="num">02</span>
                                </h5>
                                <div class="icon">
                                    <img src="{{ asset('/assets/landing/img/icons/serv_icons/2.png')}}" alt="">
                                </div>
                                <div class="info">
                                    <div class="text">
                                        Trust our top minds to eliminate workflow pain points, implement new tech & app.
                                    </div>
                                    <div class="tags">
                                        <a href="#">Management</a>
                                        <a href="#">Backup & Recovery</a>
                                        <a href="#">Transfer</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="service-box mb-4 wow fadeInUp" data-wow-delay="0.4s">
                                <h5>
                                    <a href="page-services-5.html"> Website Development </a> 
                                    <span class="num">03</span>
                                </h5>
                                <div class="icon">
                                    <img src="{{ asset('/assets/landing/img/icons/serv_icons/3.png')}}" alt="">
                                </div>
                                <div class="info">
                                    <div class="text">
                                        Trust our top minds to eliminate workflow pain points, implement new tech & app.
                                    </div>
                                    <div class="tags">
                                        <a href="#">Ecommerce</a>
                                        <a href="#">Landing Page</a>
                                        <a href="#">CMS</a>
                                        <a href="#">Plugin</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="service-box mb-4 mb-md-0 wow fadeInUp" data-wow-delay="0">
                                <h5>
                                    <a href="page-services-5.html"> UI/UX Design </a> 
                                    <span class="num">04</span>
                                </h5>
                                <div class="icon">
                                    <img src="{{ asset('/assets/landing/img/icons/serv_icons/4.png')}}" alt="">
                                </div>
                                <div class="info">
                                    <div class="text">
                                        Trust our top minds to eliminate workflow pain points, implement new tech & app.
                                    </div>
                                    <div class="tags">
                                        <a href="#">Website</a>
                                        <a href="#">Mobile App</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="service-box mb-4 mb-md-0 wow fadeInUp" data-wow-delay="0.2s">
                                <h5>
                                    <a href="page-services-5.html"> Cloud Services </a> 
                                    <span class="num">05</span>
                                </h5>
                                <div class="icon">
                                    <img src="{{ asset('/assets/landing/img/icons/serv_icons/5.png')}}" alt="">
                                </div>
                                <div class="info">
                                    <div class="text">
                                        Trust our top minds to eliminate workflow pain points, implement new tech & app.
                                    </div>
                                    <div class="tags">
                                        <a href="#">Cloud Storerage</a>
                                        <a href="#">Hosting & VPS</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
            <img src="{{ asset('/assets/landing/img/services/ser_shap_l.png')}}" alt="" class="ser_shap_l">
            <img src="{{ asset('/assets/landing/img/services/ser_shap_r.png')}}" alt="" class="ser_shap_r">
        </section>


        <section class="choose-us section-padding pt-0 style-1">
            <div class="container">
                <div class="row justify-content-end">
                    <div class="col-lg-5">
                        <div class="info">
                            <div class="section-head mb-60">
                                <h6 class="color-main text-uppercase wow fadeInUp">Why choose us</h6>
                                <h2 class="wow fadeInUp">
                                    Boost Your Business <span class="fw-normal">With New Tech</span>
                                </h2>
                            </div>
                            <div class="text">
                                Our team can assist you in transforming your business through latest tech capabilities to stay ahead of the curve.
                            </div>
                            <ul>
                                <li class="wow fadeInUp">
                                    <span class="icon">
                                        <i class="bi bi-check2"></i>
                                    </span>
                                    <h6>
                                        Latest IT Solutions & Integration 
                                    </h6>
                                </li>
                                <li class="wow fadeInUp">
                                    <span class="icon">
                                        <i class="bi bi-check2"></i>
                                    </span>
                                    <h6>
                                        Dedicated Support 24/7
                                    </h6>
                                </li>
                            </ul>
    
                            <a href="page-about-5.html" class="btn butn-gard border-0 text-white wow fadeInUp">
                                <span>How We Works</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <img src="{{ asset('/assets/landing/img/choose_us/choose_lines.svg')}}" alt="" class="choose-us-img">
            <img src="{{ asset('/assets/landing/img/choose_us/choose_brands.png')}}" alt="" class="choose-us-brands">        
            <img src="{{ asset('/assets/landing/img/choose_us/choose_bubbles.png')}}" alt="" class="choose-us-bubbles">        
        </section>

        <section class="contact section-padding bg-gradient style-1">
            <div class="container">
                <div class="section-head mb-60 text-center">
                    <h6 class="text-white text-uppercase wow fadeInUp">contact us</h6>
                    <h2 class="wow fadeInUp text-white">
                        Request Free <span class="fw-normal">Consultancy</span>
                    </h2>
                </div>
                <div class="content">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="contact_info text-white">
                                <p class="wow fadeInUp">Hotline 24/7</p>
                                <h4 class="wow fadeInUp">(+509) 5535 68 68</h4>
                                <ul>
                                    <li class="wow fadeInUp">
                                        <strong>Address : </strong> La lorraine, Rue clerveau,Petion-ville
                                    </li>
                                    <li class="wow fadeInUp">
                                        <strong>Email : </strong> contact@sagacetech.com
                                    </li>
                                   
                                    <li class="wow fadeInUp">
                                        <strong>Work Hour : </strong> Mon - Sat: 9:00 - 17:00
                                    </li>
                                </ul>
                               
                            </div>
                        </div>
                        <div class="col-lg-6 offset-lg-1">
                            <form class="contact_form" action="contact.php" method="post"> 
                                <div class="row gx-3">
                                    <div class="col-lg-6">
                                        <div class="form-group mb-3 wow fadeInUp">
                                            <input type="text" name="name" class="form-control" placeholder="full name *">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group mb-3 wow fadeInUp">
                                            <input type="email" name="email" class="form-control" placeholder="Email Address *">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group mb-3 wow fadeInUp">
                                            <input type="text" name="phone" class="form-control" placeholder="Phone number *">
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-12">
                                        <div class="form-group mb-3 wow fadeInUp">
                                            <textarea class="form-control" rows="4" placeholder="Write your inquiry here"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-check mb-4 wow fadeInUp">
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                            <label class="form-check-label text-light small" for="flexCheckDefault">
                                                By submitting, i’m agreed to the <a href="#" class="text-decoration-underline">Terms & Conditons</a>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <input type="submit" value="Request Now" class="btn btn-dark wow fadeInUp text-light fs-14px">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <img src="{{ asset('/assets/landing/img/contact_globe.svg')}}" alt="" class="contact_globe">
        </section>
    </main>
    <!--End-Contents-->

    <!-- ====== start footer ====== -->
    <footer class="style-4">
        <div class="container">
            
            <div class="foot mt-80">
                <div class="row align-items-center">
                    <div class="col-lg-2">
                        <div class="logo">
                            <img src="{{ asset('assets/landing/img/logo_lgr.png')}}" alt="">
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <ul class="links">
                            <li class="nav-item dropdown">
                                <a class="nav-link " href="/sagacetech" >
                                    Homes
                                </a>
                                
                            </li>
                            
                            <li class="nav-item">
                                <a class="nav-link" href="#service">
                                    Services
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="blog.sagacetech.com">
                                    blog
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="contact-saga">
                                    contact
                                </a>
                            </li>
                        </ul>
                    </div>
                    
                </div>
            </div>
            <div class="copywrite text-center">
                <small class="small">
                    © 2024 Copyrights by <a href="#" class="fw-bold text-decoration-underline">Sagacetech .
                </small>
            </div>
        </div>
        <img src="{{ asset('/assets/landing/img/footer/footer_4_wave.png') }}" alt="" class="wave">
    </footer>
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
