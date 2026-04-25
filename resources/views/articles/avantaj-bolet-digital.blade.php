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
        .advantage-list { background: #fff; border-left: 4px solid #3498db; padding: 20px; margin: 20px 0; }
        .advantage-list li { margin-bottom: 10px; font-size: 1.1rem; }
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
                    <li class="nav-item"><a class="nav-link btn btn-primary text-white px-4" href="/register">Eseye gratis</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="article-container">
        <article>
            <header class="article-header">
                <h1>Avantaj Bòlet Dijital an Ayiti</h1>
            </header>

            <section class="article-section">
                <h2>Gain tan konsiderab</h2>
                <p>Avèk yon sistèm bòlet dijital, ou elimine tout travay manyèl. Tikè yo anrejistre otomatikman, rezilta yo kalkile nan kèk segond, epi rapò yo jenere san efò. Sa ekonomize plizyè èdtan chak jou.</p>
            </section>

            <section class="article-section">
                <h2>Sekirite maksimòm</h2>
                <p>Sistèm dijital yo ofri yon nivo sekirite papye pa ka ba. Done yo enkripte, backup otomatik chak jou, ak aksè kontwole pa modpas. Sa pwoteje kont fwod ak pèt enfòmasyon.</p>
            </section>

            <section class="article-section">
                <h2>Rapò an tan reyèl</h2>
                <div class="advantage-list">
                    <ul>
                        <li>✓ Suiv vant ou minit pa minit</li>
                        <li>✓ Wè perfòmans chak ajan</li>
                        <li>✓ Konnen egzakteman konbyen lajan ou genyen</li>
                        <li>✓ Idantifye tikè gayan imedyatman</li>
                    </ul>
                </div>
            </section>

            <section class="article-section">
                <h2>Ekonomi lajan</h2>
                <p>Malgre ou peye yon abònman chak mwa, ou ekonomize lajan nan long tèm. Mwens papye, mwens erè, mwens moun pou anplwaye, ak mwens risk fè pèt lajan. Majorite konpayi wè yon retou sou envèstisman yo nan mwens pase 3 mwa.</p>
            </section>

            <section class="article-section">
                <h2>Aksè mobil pou ajan yo</h2>
                <p>Ajan ak vendè yo ka itilize telefòn mobil yo pou anrejistre tikè. Yo pa bezwen vin nan biwo ou chak fwa. Sa ogmante pwodiktivite yo epi pèmèt ou agrandi rezèt ou.</p>
            </section>

            <div class="cta-section">
                <h3>Pase nan dijital jodi a</h3>
                <p>Rejwenn santèn konpayi ki deja modernize ak Sagaloto</p>
                <a href="/register" class="cta-btn">Kòmanse modernizasyon ou</a>
            </div>

            <div class="related-articles">
                <h3>Atik ki gen rapò</h3>
                <ul>
                    <li><a href="/systeme-bolet-haiti">📄 Sistèm Bòlet an Ayiti</a></li>
                    <li><a href="/kijan-jere-bolet">📄 Kijan pou jere yon bòlet</a></li>
                    <li><a href="/logiciel-bolet">📄 Lojisyèl Bòlet</a></li>
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
