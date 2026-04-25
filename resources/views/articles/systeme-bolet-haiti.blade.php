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

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="{{ asset('/assets/landing/css/lib/bootstrap.min.css')}}">

    <!-- Font family -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('/assets/landing/css/lib/all.min.css')}}">
    <link rel="stylesheet" href="{{ asset('/assets/landing/css/style.css')}}">

    <style>
        .article-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 60px 20px;
        }
        .article-header {
            margin-bottom: 40px;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 20px;
        }
        .article-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 20px;
            line-height: 1.3;
        }
        .article-section {
            margin-bottom: 40px;
        }
        .article-section h2 {
            font-size: 1.8rem;
            font-weight: 600;
            color: #34495e;
            margin-bottom: 15px;
            margin-top: 30px;
        }
        .article-section p {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #555;
            text-align: justify;
        }
        .related-articles {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 8px;
            margin-top: 50px;
        }
        .related-articles h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: #2c3e50;
        }
        .related-articles ul {
            list-style: none;
            padding: 0;
        }
        .related-articles li {
            margin-bottom: 12px;
        }
        .related-articles a {
            color: #3498db;
            text-decoration: none;
            font-size: 1.1rem;
            transition: color 0.3s;
        }
        .related-articles a:hover {
            color: #2980b9;
            text-decoration: underline;
        }
        .cta-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            border-radius: 8px;
            text-align: center;
            margin-top: 40px;
        }
        .cta-section h3 {
            font-size: 1.8rem;
            margin-bottom: 15px;
        }
        .cta-section p {
            font-size: 1.1rem;
            margin-bottom: 25px;
        }
        .cta-btn {
            background: white;
            color: #667eea;
            padding: 12px 35px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            transition: transform 0.3s;
        }
        .cta-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .breadcrumb {
            padding: 20px 0;
            font-size: 0.9rem;
        }
        .breadcrumb a {
            color: #3498db;
            text-decoration: none;
        }
        .breadcrumb a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img src="{{ asset('/assets/landing/img/saga.png')}}" alt="Sagaloto" style="height: 40px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Akèy</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/contact">Kontakte nou</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary text-white px-4" href="/register">Eseye gratis</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Breadcrumb -->
    <div class="container">
        <div class="breadcrumb">
            <a href="/">Akèy</a> / <span>{{ $article['h1'] }}</span>
        </div>
    </div>

    <!-- Article Content -->
    <div class="article-container">
        <article>
            <!-- Article Header -->
            <header class="article-header">
                <h1>{{ $article['h1'] }}</h1>
            </header>

            <!-- Article Sections -->
            @foreach($article['sections'] as $section)
            <section class="article-section">
                <h2>{{ $section['h2'] }}</h2>
                <p>{{ $section['content'] }}</p>
            </section>
            @endforeach

            <!-- CTA Section -->
            <div class="cta-section">
                <h3>Kòmanse avèk Sagaloto jodi a</h3>
                <p>Enskri gratis epi modernize jesyon bòlet ou an 5 minit</p>
                <a href="/register" class="cta-btn">Eseye gratis</a>
            </div>

            <!-- Related Articles -->
            @if(isset($article['relatedArticles']) && count($article['relatedArticles']) > 0)
            <div class="related-articles">
                <h3>Atik ki gen rapò</h3>
                <ul>
                    @foreach($article['relatedArticles'] as $relatedArticle)
                    <li>
                        <a href="{{ $relatedArticle['url'] }}">📄 {{ $relatedArticle['title'] }}</a>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
        </article>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p>&copy; {{ date('Y') }} Sagaloto - Tout dwa rezève</p>
            <p>
                <a href="/contact" class="text-white">Kontakte nou</a> |
                <a href="/" class="text-white">Akèy</a>
            </p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="{{ asset('/assets/landing/js/lib/jquery-3.0.0.min.js')}}"></script>
    <script src="{{ asset('/assets/landing/js/lib/bootstrap.bundle.min.js')}}"></script>
</body>
</html>
