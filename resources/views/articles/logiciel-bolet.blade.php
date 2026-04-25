<!DOCTYPE html>
<html lang="ht">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <title>{{ $article['title'] }}</title>
    <meta name="description" content="{{ $article['description'] }}">
    <meta name="keywords" content="{{ $article['keywords'] }}">
    <meta name="author" content="Sagaloto">

    <link rel="shortcut icon" href="{{ asset('/assets/landing/img/saga.png')}}" title="Favicon">
    <link rel="stylesheet" href="{{ asset('/assets/landing/css/lib/bootstrap.min.css')}}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/assets/landing/css/lib/all.min.css')}}">
    <link rel="stylesheet" href="{{ asset('/assets/landing/css/style.css')}}">

    <style>
        .article-container { max-width: 900px; margin: 0 auto; padding: 60px 20px; }
        .article-header { margin-bottom: 40px; border-bottom: 2px solid #e9ecef; padding-bottom: 20px; }
        .article-header h1 { font-size: 2.5rem; font-weight: 700; color: #2c3e50; margin-bottom: 20px; }
        .article-section { margin-bottom: 40px; }
        .article-section h2 { font-size: 1.8rem; font-weight: 600; color: #34495e; margin-bottom: 15px; }
        .article-section p { font-size: 1.1rem; line-height: 1.8; color: #555; text-align: justify; }
        .related-articles { background: #f8f9fa; padding: 30px; border-radius: 8px; margin-top: 50px; }
        .related-articles h3 { font-size: 1.5rem; font-weight: 600; margin-bottom: 20px; }
        .related-articles a { color: #3498db; text-decoration: none; font-size: 1.1rem; }
        .related-articles a:hover { color: #2980b9; text-decoration: underline; }
        .cta-section { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 40px; border-radius: 8px; text-align: center; margin-top: 40px; }
        .cta-btn { background: white; color: #667eea; padding: 12px 35px; border-radius: 25px; text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="/"><img src="{{ asset('/assets/landing/img/saga.png')}}" alt="Sagaloto" style="height: 40px;"></a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="/">Akèy</a></li>
                    <li class="nav-item"><a class="nav-link" href="/contact">Kontakte nou</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-primary text-white px-4" href="/contact">📞 +509 31-07-1890</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="article-container">
        <article>
            <header class="article-header">
                <h1>Lojisyèl Bòlet pou Ayiti</h1>
            </header>

            <section class="article-section">
                <h2>Sa ki yon lojisyèl bòlet?</h2>
                <p>Yon lojisyèl bòlet se yon aplikasyon ki pèmèt otomatize tout aspè jesyon yon lotri. Li ranplase sistèm papye tradisyonèl yo pa yon platfòm dijital rapid, sekirize ak fasil pou itilize.</p>
            </section>

            <section class="article-section">
                <h2>Karakteristik enpòtan yon lojisyèl bòlet</h2>
                <p>Yon bon lojisyèl bòlet dwe gen: anrejistreman tikè rapid, jesyon plizyè ajan ak vendè, kalkil otomatik rezilta, rapò detaye, sekirite done, ak aksè mobil. Sagaloto gen tout sa ak plis ankò.</p>
            </section>

            <section class="article-section">
                <h2>Poukisa chwazi yon lojisyèl lokal?</h2>
                <p>Yon lojisyèl lokal tankou Sagaloto konprann reyalite biznis bòlet ayisyen yo. Sipò lokal 24/7, pa gen pwoblèm lang, ak fonksyonalite ki adapte pou mache ayisyen an.</p>
            </section>

            <section class="article-section">
                <h2>Konbyen koute yon lojisyèl bòlet?</h2>
                <p>Sagaloto ofri plizyè plan abònman ki adapte pou ti ak gwo konpayi. Ou ka kòmanse avèk yon eseye gratis epi chwazi plan ki pi bon pou ou apre. Pa gen frè kache.</p>
            </section>

            <div class="cta-section">
                <h3>Dekouvri Sagaloto jodi a</h3>
                <p>Kontakte nou pou plis enfòmasyon sou lojisyèl la</p>
                <div style="margin-bottom: 15px;">
                    <p style="font-size: 1.2rem; margin-bottom: 10px;">📞 +509 31-07-1890</p>
                    <p style="font-size: 1.2rem;">📧 contact@sagaloto.com</p>
                </div>
                <a href="/contact" class="cta-btn">Kontakte nou</a>
            </div>

            <div class="related-articles">
                <h3>Atik ki gen rapò</h3>
                <ul>
                    <li><a href="/systeme-bolet-haiti">📄 Sistèm Bòlet an Ayiti</a></li>
                    <li><a href="/kijan-jere-bolet">📄 Kijan pou jere yon bòlet</a></li>
                    <li><a href="/avantaj-bolet-digital">📄 Avantaj Bòlet Dijital</a></li>
                </ul>
            </div>
        </article>
    </div>

    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p>&copy; {{ date('Y') }} Sagaloto - Tout dwa rezève</p>
        </div>
    </footer>

    <script src="{{ asset('/assets/landing/js/lib/jquery-3.0.0.min.js')}}"></script>
    <script src="{{ asset('/assets/landing/js/lib/bootstrap.bundle.min.js')}}"></script>
</body>
</html>
