<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="keywords" content="Sagacetech, SAGALOTO pos, POS haiti,">
    <meta name="description" content="SAGALOTO application POS bolot , yon pwodwi de Sagacetech">
    <meta name="author" content="Sagacetech">
        
    <title>Sagaloto</title>

        <link rel="shortcut icon" href="{{ asset('/assets/landing/img/saga.png')}}" title="Favicon" >
        <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico')}}" />
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
            <div id="loading-text"> <img src="{{ asset('/assets/imagess/logo.png')}}" alt=""> </div>
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
                <span class="fs-10px op-6">jiska 30 Jiye</span>
                <a href="/contact" class="fs-10px text-decoration-underline ms-2">Pase komand</a>
            </div>
        </div>
    </div>
    <!-- ====== end top navbar ====== -->

    <!-- ====== start navbar ====== -->
   @include('navbar')
    <!-- ====== end navbar ====== -->

    <!-- ====== start header ====== -->
    <header class="style-4">
        <div class="content">
            <div class="container">
                <div class="row gx-0">
                    <div class="col-lg-6">
                        <div class="info">
                            <small class="mb-50 title_small">Sagaloto - POS App</small>
                            <h1 class="mb-30">Aplikasyon <span> POS Bolet</span>  </h1>
                            <p class="text">Vann pi rapid , e gen kontwol tout tranzaksyon yo. A pati de 30 POS ou mande pesonalize saw vle gratis</p>
                            <div class="d-flex align-items-center mt-50">
                                <a href="https://www.apple.com/app-store/" class="btn rounded-pill bg-blue4 fw-bold text-white me-4" target="_blank">
                                    <small> <i class="fab fa-android me-2 pe-2 border-end"></i>Telechaje Aplikasyon an </small>
                                </a>
                                <a href="https://youtu.be/pGbIOC83-So?t=21" data-lity="" class="play-btn">
                                    <span class="icon me-2">
                                        <i class="fas fa-play ms-1"></i>
                                    </span>
                                    <strong class="small">Gade <br> Pwomosyon</strong>
                                </a>
                            </div>
                            <span class="mt-100 me-5">
                                <small class="icon-30 bg-gray rounded-circle color-blue4 d-inline-flex align-items-center justify-content-center me-1">
                                    <i class="fas fa-sync"></i>
                                </small>
                                <small class="text-uppercase">Demonstrasyon Gratis</small>
                            </span>
                            <span class="mt-100">
                                <small class="icon-30 bg-gray rounded-circle color-blue4 d-inline-flex align-items-center justify-content-center me-1">
                                    <i class="fas fa-credit-card"></i>
                                </small>
                                <small style="display: none;" class="text-uppercase">ou ka Peye abonmanw an liy tou</small>
                            </span>
                        </div>
                    </div>
                    <div class="col-lg-6" style="margin-top: -66px;">
                        <div class="img">
                            <img src="{{ asset('/assets/landing/img/header/header_4.png')}}" alt="">
                        </div>
                    </div>
                </div>
            </div>
            <img src="{{ asset('/assets/landing/img/header/header_4_bubble.png')}}" alt="" class="bubble">
        </div>
        <img src="{{ asset('/assets/landing/img/header/header_4_wave.png')}}" alt="" class="wave">
    </header>
    <!-- ====== end header ====== -->

    <!--Contents-->
    <main>

    

        <!-- ====== start features ====== -->
        <section class="features pt-70 pb-70 style-4">
            <div class="container">
                <div class="section-head text-center style-4">
                    <small class="title_small bg-white">Sagaloto - Aplikasyon POS Bolet</small>
                    
                </div>
                <div class="content">
                    <div class="features-card">
                        <div class="icon img-contain">
                            <img src="{{ asset('/assets/landing/img/icons/fe1.png')}}" alt="">
                        </div>
                        <h6>Tout tranzaksyon ak done yo<br>Sekirize</h6>
                    </div>
                    <div class="features-card">
                        <div class="icon img-contain">
                            <img src="{{ asset('/assets/landing/img/icons/fe2.png')}}" alt="">
                        </div>
                        <h6>sistem verifikasyon AI <br></h6>
                    </div>
                    <div class="features-card">
                        <div class="icon img-contain">
                            <img src="{{ asset('/assets/landing/img/icons/fe3.png')}}" alt="">
                            <span class="label icon-40 alert-success text-success rounded-circle small text-uppercase fw-bold">
                                new
                            </span>
                        </div>
                        <h6>Yon ekip ki travay avek anpil kolaborasyon<br></h6>
                    </div>
                   
                </div>
            </div>
            <img src="{{ asset('/assets/landing/img/feat_circle.png')}}" alt="" class="img-circle">
        </section>
        <!-- ====== end features ====== -->

        <!-- ====== start about ====== -->
        <section class="about section-padding style-4"  id="applicationsaga">
            <div class="content frs-content">
                <div class="container">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-lg-6">
                            <div class="img mb-30 mb-lg-0">
                                <img src="{{ asset('/assets/landing/img/about/ipad.png')}}" alt="">
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="info">
                                <div class="section-head style-4">
                                    <small class="title_small">Sagaloto-Dashboard </small>
                                    <h2 class="mb-30">Espas <span>pou gere POS yo </span> </h2>
                                </div>
                                <p class="text mb-40">
                                   Yon panel pou jere tout POS ou gen ki ap vann bolet , ak met kontwol sou tout vant.  </p>
                                <ul>
                                    <li class="d-flex align-items-center mb-3">
                                        <small class="icon-30 bg-gray rounded-circle color-blue4 d-inline-flex align-items-center justify-content-center me-3">
                                            <i class="fas fa-tag"></i>
                                        </small>
                                        <h6 class="fw-bold">Jere POS ou yo janw vle, bloke, debloke.</h6>
                                    </li>
                                    <li class="d-flex align-items-center mb-3">
                                        <small class="icon-30 bg-gray rounded-circle color-blue4 d-inline-flex align-items-center justify-content-center me-3">
                                            <i class="fas fa-sync"></i>
                                        </small>
                                        <h6 class="fw-bold">Rapò tout van.</h6>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <small class="icon-30 bg-gray rounded-circle color-blue4 d-inline-flex align-items-center justify-content-center me-3">
                                            <i class="fas fa-text-width"></i>
                                        </small>
                                        <h6 class="fw-bold">Jere boul kap jwe , met kontwòl</h6>
                                    </li>
                                </ul>
                                <a href="page-contact-5.html" class="btn rounded-pill bg-blue4 fw-bold text-white mt-50">
                                    <small>Anrejistre pou POS paw lan</small>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <img src="{{ asset('/assets/landing/img/about/about_s4_lines.png')}}" alt="" class="lines">
                <img src="{{ asset('/assets/landing/img/about/about_s4_bubble.png')}}" alt="" class="bubble">
            </div>
            <div class="content sec-content">
                <div class="container">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-lg-5 order-2 order-lg-0">
                            <div class="info">
                                <div class="section-head style-4">
                                    <small class="title_small">Yon sistèm konplè, saw bezwen mandel</small>
                                    <h2 class="mb-30">Sagaloto <span> POS APP</span> </h2>
                                </div>
                                <p class="text mb-40">
                                    Nouvo entefas, sistèm otmatik ,anpil nouvote. Vann pi rapid avèk plis konfyans nan rapò ou.
                                </p>
                                <div class="faq style-3 style-4">
                                    <div class="accordion" id="accordionExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading1">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                                                   Jwe boul pou kreye fich pou kliyan yo.
                                                </button>
                                            </h2>
                                            <div id="collapse1" class="accordion-collapse collapse show" aria-labelledby="heading1" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                   Loto senp, maryaj otomatik, maryaj plizyè boul. Loto 4 rapid rapid, Modifikasyon rapid . San ke sote gen kontwol sa wap fe an 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading2">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                                                   Kontwòl rapò.
                                                </button>
                                            </h2>
                                            <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="heading2" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                   Kontwòl rapò ou rapid,  Plizyè flitè pou jwenn rapò jounen ou. ak tout lot jou yo.  E nan kèk jou ou ka pwograme rapo ou poul soti chak le bolot fenmen .
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading3">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                                                    Evite pèt,Tiraj bolot.
                                                </button>
                                            </h2>
                                            <div id="collapse3" class="accordion-collapse collapse" aria-labelledby="heading3" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                   Tout tiraj Bòlet pou jounen epi soti 1 Janvye 2024 Disponib sou Aplikasyon pa mwa, Pwoblem kesyon bezwen konn sak soti rezoud. Epi avan tout tiraj fich ganyan sistem met kontrol pou we si gen ere nan met boul tiraj yo.  
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a href="https://chrome.google.com/webstore/category/extensions" class="btn btn-img mt-40 rounded-pill" target="_blank">
                                    <div class="icon img-contain">
                                        <img src="{{ asset('/assets/landing/img/icons/chrome_icon.png')}}" alt="">
                                    </div>
                                    <div class="inf">
                                        <small>Disponib </small>
                                        <h6>Telechaje Aplikasyon an</h6>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-6 order-0 order-lg-2">
                            <div class="img mb-30 mb-lg-0">
                                <img src="{{ asset('/assets/landing/img/about/2mobiles.png')}}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
                <img src="{{ asset('/assets/landing/img/about/about_s4_bubble2.png')}}" alt="" class="bubble2">
            </div>
        </section>
    
        <section class="pricing section-padding style-4 pb-50">
            <div class="container">
                <div class="section-head text-center style-4">
                    <small class="title_small">Sagaloto Plan</small>
                    <h2 class="mb-30"> Demare yon pri <span>konfòtab</span> </h2>
                </div>
                <div class="content">
                    <div class="toggle_switch d-flex align-items-center justify-content-center mb-40">
                        <div class="form-check form-switch p-0">
                            <label class="form-check-label" for="flexSwitchCheckDefault"><small>peye pa mwa</small></label>
                            <input class="form-check-input float-none bg-blue4" type="checkbox" id="flexSwitchCheckDefault" checked="">
                            <label class="form-check-label" for="flexSwitchCheckDefault"><small>peye
                                    pa trimès</small></label>
                        </div>
                    </div>
                    <div class="row gx-0">
                        <div class="col-lg-6">
                            <div class="price-card">
                                <div class="price-header pb-4">
                                    <h6> <img src="{{ asset('/assets/landing/img/icons/price_s4_1.png')}}" alt="" class="icon"> basic Plan </h6>
                                    <h2>10 $<small> / mwa</small></h2>
                                    <p>Sipo teknik e asistans rapid</p>
                                </div>
                                <div class="price-body py-4">
                                    <ul>
                                        <li class="d-flex align-items-center mb-3">
                                            <small class="icon-30 bg-blue4 rounded-circle text-white d-inline-flex align-items-center justify-content-center me-3 flex-shrink-0">
                                                <i class="far fa-sticky-note"></i>
                                            </small>
                                            <p class="fw-bold">Enstalasyon Gratis </p>
                                        </li>
                                        <li class="d-flex align-items-center mb-3">
                                            <small class="icon-30 bg-blue4 rounded-circle text-white d-inline-flex align-items-center justify-content-center me-3 flex-shrink-0">
                                                <i class="fas fa-paperclip"></i>
                                            </small>
                                            <p class="fw-bold">1 Sipè Admin</p>
                                        </li>
                                        <li class="d-flex align-items-center mb-3">
                                        <small class="icon-30 bg-blue4 rounded-circle text-white d-inline-flex align-items-center justify-content-center me-3 flex-shrink-0">
                                            <i class="fas fa-paperclip"></i>
                                        </small>
                                        <p class="fw-bold">5 repasyon POS gratis/Mwa</p>
                                       </li>
                                        <li class="d-flex align-items-center mb-3">
                                            <small class="icon-30 bg-blue4 rounded-circle text-white d-inline-flex align-items-center justify-content-center me-3 flex-shrink-0">
                                                <i class="fas fa-lock"></i>
                                            </small>
                                            <p class="fw-bold">sekirite ranfose</p>
                                        </li>
                                        <li class="d-flex align-items-center mb-3">
                                            <small class="icon-30 bg-blue4 rounded-circle text-white d-inline-flex align-items-center justify-content-center me-3 flex-shrink-0">
                                                <i class="fas fa-undo"></i>
                                            </small>
                                            <p class="fw-bold">Rapo tranzaksyon nenpot moman</p>
                                        </li>
                                        <li class="d-flex align-items-center op-3">
                                            <small class="icon-30 bg-blue4 rounded-circle text-white d-inline-flex align-items-center justify-content-center me-3 flex-shrink-0">
                                                <i class="fas fa-download"></i>
                                            </small>
                                            <p class="fw-bold">Export rapo PDF,</p>
                                        </li>
                                    </ul>
                                    <a href="https://wa.me/46538901" class="btn rounded-pill hover-blue4 fw-bold mt-50 px-5 border-blue4">
                                        <small>Kontakte la sou Whatsapp pou Anregistre </small>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="price-card">
                                <div class="price-header pb-4">
                                    <h6> <img src="{{ asset('/assets/landing/img/icons/price_s4_2.png')}}" alt="" class="icon"> premium Plan </h6>
                                    <h2>$27<small> / trimèst</small></h2>
                                    <p>Tout aksè sou sistem nan. sipo e asistans rapid</p>
                                </div>
                                <div class="price-body py-4">
                                    <ul>
                                        <li class="d-flex align-items-center mb-3">
                                            <small class="icon-30 bg-blue4 rounded-circle text-white d-inline-flex align-items-center justify-content-center me-3 flex-shrink-0">
                                                <i class="far fa-sticky-note"></i>
                                            </small>
                                            <p class="fw-bold">Enstalasyon gratis</p>
                                        </li>
                                        <li class="d-flex align-items-center mb-3">
                                            <small class="icon-30 bg-blue4 rounded-circle text-white d-inline-flex align-items-center justify-content-center me-3 flex-shrink-0">
                                                <i class="fas fa-paperclip"></i>
                                            </small>
                                            <p class="fw-bold">2+ super admin</p>
                                        </li>
                                        <li class="d-flex align-items-center mb-3">
                                            <small class="icon-30 bg-blue4 rounded-circle text-white d-inline-flex align-items-center justify-content-center me-3 flex-shrink-0">
                                                <i class="fas fa-paperclip"></i>
                                            </small>
                                            <p class="fw-bold">Reparasyon POS gratis</p>
                                           </li>
                                        <li class="d-flex align-items-center mb-3">
                                            <small class="icon-30 bg-blue4 rounded-circle text-white d-inline-flex align-items-center justify-content-center me-3 flex-shrink-0">
                                                <i class="fas fa-lock"></i>
                                            </small>
                                            <p class="fw-bold">Sekirite ranfose</p>
                                        </li>
                                        <li class="d-flex align-items-center mb-3">
                                            <small class="icon-30 bg-blue4 rounded-circle text-white d-inline-flex align-items-center justify-content-center me-3 flex-shrink-0">
                                                <i class="fas fa-undo"></i>
                                            </small>
                                            <p class="fw-bold">Rapo tranzaksyon nenpot moman</p>
                                        </li>
                                       
                                        <li class="d-flex align-items-center op-3">
                                            <small class="icon-30 bg-blue4 rounded-circle text-white d-inline-flex align-items-center justify-content-center me-3 flex-shrink-0">
                                                <i class="fas fa-download"></i>
                                            </small>
                                            <p class="fw-bold">Export rapo PDF,</p>
                                        </li>
                                    </ul>
                                    <a href="https://wa.me/46538901" class="btn rounded-pill bg-blue4 fw-bold text-white px-5 mt-50">
                                        <small>Kontakte la sou Whatsapp pou Anregistre klike</small>
                                    </a>
                                </div>
                                <div class="off">
                                    <span>
                                        10% <br> off
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- ====== end pricing ====== -->
        <section class="testimonials style-4 pt-70">
            
            <div class="container">
                <div class="content">
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="section-head style-4">
                                <small class="title_small">Temwanyaj</small>
                                <h2 class="mb-30">Kontantman<span>kliyan nou</span> </h2>
                            </div>
                            <p class="text mb-40">
                               Sagaloto sevis de Sagacetech ,Best konpani nan teknoloji an Haiti.
                            </p>
                            <div class="numbs">
                                <div class="num-card">
                                    <div class="icon img-contain">
                                        <img src="{{ asset('/assets/landing/img/icons/testi_s4_ic1.png')}}" alt="">
                                    </div>
                                    
                                    <p>Telechaje aplikasyon nou <br>oubyen mande kreye paw lan</p>
                                </div>
                                
                            </div>
                            <div class="d-flex align-items-center mt-70">
                                <a href="/contact" class="btn rounded-pill bg-blue4 fw-bold text-white me-4" target="_blank">
                                    <small> Kontakte nou. </small>
                                </a>
                                <a href="https://youtu.be/pGbIOC83-So?t=21" data-lity="" class="play-btn">
                                    <span class="icon me-2">
                                        <i class="fas fa-play ms-1"></i>
                                    </span>
                                    <strong class="small">Gade<br> pwomosyon nou</strong>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="testi-cards">
                                <div class="client_card">
                                    <div class="user_img">
                                        <img src="{{ asset('/assets/landing/img/testimonials/user4.png')}}" alt="">
                                    </div>
                                    <div class="inf_content">
                                        <div class="stars mb-2">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <h6>
                                            “Ti mesye mwen renmen Aplikasyon nou an <br>Se li  map use map tann tout amelyorasyon nou yo.”
                                        </h6>
                                        <p>Junionr J.<span class="text-muted"> /Ajan bolot </span></p>
                                    </div>
                                </div>
                                <div class="client_card">
                                    <div class="user_img">
                                        <img src="{{ asset('/assets/landing/img/testimonials/user5.png')}}" alt="">
                                    </div>
                                    <div class="inf_content">
                                        <div class="stars mb-2">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <h6>
                                            “Youn nan sevis POS vant boutik nan boutik ki manm bon sevis san kanpe,SVP fe plis moun jwenn nou”
                                        </h6>
                                        <p>Sandra Perin <span class="text-muted"> / Vendeuse <span>SandraBoutik</span> </span></p>
                                    </div>
                                </div>
                                <div class="client_card">
                                    <div class="user_img">
                                        <img src="{{ asset('/assets/landing/img/testimonials/user6.png')}}" alt="">
                                    </div>
                                    <div class="inf_content">
                                        <div class="stars mb-2">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <h6>
                                            “.Mesi pou aplikasyon ...... nou develope pou mwen Sagacetech<br> Li satisfem.”                                    </h6>
                                        <p>Joseph  T. <span class="text-muted"> /Economiste<span>Teckzone</span> </span></p>
                                    </div>
                                </div>
                                <img src="{{ asset('/assets/landing/img/contact_globe.svg')}}" alt="" class="testi-globe">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- ====== start faq ====== -->
        <section class="faq section-padding style-4 pt-50">
            <div class="container">
                <div class="section-head text-center style-4">
                    <small class="title_small">Kesyon ke kliyan poze souvan</small>
                    <h2 class="mb-30"> Yon ti <span> Sipo? </span> </h2>
                </div>
                <div class="content">
                    <div class="faq style-3 style-4">
                        <div class="accordion" id="accordionSt4">
                            <div class="row gx-5">
                                <div class="col-lg-6">
                                    <div class="accordion-item border-bottom rounded-0">
                                        <h2 class="accordion-header" id="heading11">
                                            <button class="accordion-button collapsed rounded-0 py-4" type="button" data-bs-toggle="collapse" data-bs-target="#collapse11" aria-expanded="true" aria-controls="collapse11">
                                               Ki rezon fe se nou oblije chwazi?
                                            </button>
                                        </h2>
                                        <div id="collapse11" class="accordion-collapse collapse rounded-0" aria-labelledby="heading11" data-bs-parent="#accordionSt4">
                                            <div class="accordion-body">
                                              Repons: Chaje sou mache, men ki rezon fe wap chache yon lot sistem.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item border-bottom rounded-0">
                                        <h2 class="accordion-header" id="heading12">
                                            <button class="accordion-button py-4" type="button" data-bs-toggle="collapse" data-bs-target="#collapse12" aria-expanded="false" aria-controls="collapse12">
                                                Poukisa nou che konsa?
                                            </button>
                                        </h2>
                                        <div id="collapse12" class="accordion-collapse collapse show rounded-0" aria-labelledby="heading12" data-bs-parent="#accordionSt4">
                                            <div class="accordion-body">
                                                Repons: Nou chache bay yon sevis de kalite, se pa jis fel mache, men fel mache byen , pou reponn ak  bezwen chak pwopriyète.
                                            </div>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="col-lg-6">
                                    <div class="accordion-item border-bottom rounded-0">
                                        <h2 class="accordion-header" id="heading13">
                                            <button class="accordion-button collapsed py-4" type="button" data-bs-toggle="collapse" data-bs-target="#collapse13" aria-expanded="false" aria-controls="collapse13">
                                               Eskem ka itilize aplikasyon avek android mwen?
                                            </button>
                                        </h2>
                                        <div id="collapse13" class="accordion-collapse collapse rounded-0" aria-labelledby="heading13" data-bs-parent="#accordionSt4">
                                            <div class="accordion-body">
                                               Repons: Ah wi, se fasil depi nan kreye kont ou nap bezwen ID telefon ou an. sa kap pemet ou vann, e konekte yon ti printer  </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item border-0 rounded-0">
                                        <h2 class="accordion-header" id="heading4">
                                            <button class="accordion-button collapsed rounded-0 py-4" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="true" aria-controls="collapse4">
                                               Eske sistem nan sekirize lem bezwen bloke yon vandè?
                                            </button>
                                        </h2>
                                        <div id="collapse4" class="accordion-collapse collapse rounded-0" aria-labelledby="heading4" data-bs-parent="#accordionSt4">
                                            <div class="accordion-body">
                                                Repons: Pwopriyetè an gen yon Dashboard konple kotel gen kontwol tout sistèm nan.     </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- ====== end faq ====== -->

        <!-- ====== start community ====== -->
        <section class="community section-padding pt-0 style-4">
            <div class="container">
                <div class="section-head text-center style-4">
                    <small class="title_small">Sagacetech  kominote nou</small>
                    <h2 class="mb-30"> Rejwenn <span> kominote nou </span> </h2>
                </div>
                <div class="content">
                    <a href="https://facebook.com/sagacetech" class="commun-card">
                        <div class="icon">
                            <i class="fab fa-facebook"></i>
                        </div>
                        <div class="inf">
                            <h5>Facebook</h5>
                            <p>Swiv nou sou kominote Facebook lan</p>
                        </div>
                    </a>
                    
                    <a href="https://wa.me/46538901" class="commun-card">
                        <div class="icon">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <div class="inf">
                            <h5>Whatsapp Sagaloto</h5>
                            <p>Swiv nou sou chanel Loto Sagaloto lan</p>
                        </div>
                    </a>
                    
                </div>
            </div>
        </section>
        <!-- ====== end community ====== -->
    </main>
    <!--End-Contents-->

    <!-- ====== start footer ====== -->
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
