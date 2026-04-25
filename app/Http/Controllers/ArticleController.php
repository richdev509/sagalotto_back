<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Show the systeme bolet haiti article
     */
    public function systemeBoletHaiti()
    {
        $article = [
            'title' => 'Sistèm Bòlet an Ayiti | Lojisyèl lotri modèn - Sagaloto',
            'description' => 'Dekouvri pi bon sistèm bòlet an Ayiti. Sagaloto pèmèt jere tikè, ajan ak tiraj fasilman. Rapid, sekirize ak pwofesyonèl.',
            'keywords' => 'bòlet haiti, sistem bolet, lotri haiti, gestion loterie haiti',
            'h1' => 'Sistèm Bòlet an Ayiti : Solisyon modèn pou jere lotri',
            'sections' => [
                [
                    'h2' => 'Kisa yon sistèm bòlet ye ?',
                    'content' => 'Yon sistèm bòlet se yon platfòm dijital ki pèmèt jere tout aspè yon lotri oswa bòlet. Li enkli jesyon tikè, ajan, vendè, tiraj ak rapò finansye.'
                ],
                [
                    'h2' => 'Kijan pou jere yon bòlet an Ayiti',
                    'content' => 'Pou jere yon bòlet an Ayiti efektivman, ou bezwen yon sistèm ki ka: anrejistre tikè an tan reyèl, jere plizyè ajan ak vendè, kalkile rezilta tiraj otomatikman, epi bay rapò finansye detaye.'
                ],
                [
                    'h2' => 'Poukisa sistèm tradisyonèl yo pa efikas',
                    'content' => 'Sistèm tradisyonèl yo baze sou papye ak kalkil manyèl. Sa lakoz pèt tan, erè nan kalkil, difikilte pou suiv vant, epi riski fwod. Modernizasyon avèk yon sistèm dijital rezoud tout pwoblèm sa yo.'
                ],
                [
                    'h2' => 'Avantaj yon sistèm bòlet dijital',
                    'content' => 'Yon sistèm bòlet dijital ofri plizyè avantaj: jesyon otomatik tikè, sekirite done, rapò an tan reyèl, aksè mobil pou ajan, ak enposibilite fè fwod. Sagaloto ofri tout karakteristik sa yo.'
                ],
                [
                    'h2' => 'Poukisa chwazi Sagaloto',
                    'content' => 'Sagaloto se sistèm bòlet nimewo 1 an Ayiti. Li rapid, sekirize, fasil pou itilize, ak sipò lokal 24/7. Plis pase 100 konpayi deja fè konfyans nou pou jere biznis bòlet yo.'
                ]
            ],
            'relatedArticles' => [
                [
                    'url' => '/kijan-jere-bolet',
                    'title' => 'Kijan pou jere yon bòlet'
                ],
                [
                    'url' => '/logiciel-bolet',
                    'title' => 'Lojisyèl Bòlet'
                ],
                [
                    'url' => '/avantaj-bolet-digital',
                    'title' => 'Avantaj Bòlet Dijital'
                ]
            ]
        ];

        return view('articles.systeme-bolet-haiti', compact('article'));
    }

    /**
     * Show the kijan jere bolet article
     */
    public function kijanJereBolet()
    {
        $article = [
            'title' => 'Kijan pou jere yon bòlet | Gid konplè - Sagaloto',
            'description' => 'Aprann kijan pou jere yon bòlet fasil ak Sagaloto. Gid konplè pou jesyon tikè, ajan, vendè ak tiraj.',
            'keywords' => 'jere bolet, gestion bolet haiti, administrer loterie'
        ];

        return view('articles.kijan-jere-bolet', compact('article'));
    }

    /**
     * Show the logiciel bolet article
     */
    public function logicielBolet()
    {
        $article = [
            'title' => 'Lojisyèl Bòlet | Meye aplikasyon pou lotri - Sagaloto',
            'description' => 'Dekouvri lojisyèl bòlet Sagaloto. Pwofesyonèl, rapid ak sekirize pou jere tout biznis lotri ou an Ayiti.',
            'keywords' => 'logiciel bolet, application loterie haiti, software bolet'
        ];

        return view('articles.logiciel-bolet', compact('article'));
    }

    /**
     * Show the avantaj bolet digital article
     */
    public function avantajBoletDigital()
    {
        $article = [
            'title' => 'Avantaj Bòlet Dijital | Poukisa modernize - Sagaloto',
            'description' => 'Konnen tout avantaj yon sistèm bòlet dijital. Ekonomize tan, lajan ak ogmante sekirite biznis ou.',
            'keywords' => 'avantaj bolet digital, modernisation loterie haiti, technologie bolet'
        ];

        return view('articles.avantaj-bolet-digital', compact('article'));
    }
}
