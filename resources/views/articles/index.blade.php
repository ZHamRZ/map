<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('seo.articles_title') }}</title>
    <meta name="description" content="{{ __('seo.articles_desc') }}">
    <meta property="og:title" content="{{ __('seo.articles_title') }}">
    <meta property="og:description" content="{{ __('seo.articles_desc') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:card" content="summary_large_image">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8faf8; color: #2b3d32; }
        .navbar { box-shadow: 0 2px 12px rgba(0,0,0,0.1); z-index: 2000; }
        .page-header {
            background: linear-gradient(135deg, #0b2e1b 0%, #1b5e20 50%, #2e7d32 100%);
            color: white; padding: 50px 0 40px; text-align: center;
        }
        .page-header h1 { font-weight: 800; font-size: 2rem; }
        .article-card {
            background: white; border-radius: 16px; overflow: hidden;
            box-shadow: 0 4px 16px rgba(0,0,0,0.04); border: 1px solid rgba(0,0,0,0.04);
            transition: all 0.3s ease; height: 100%;
        }
        .article-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(27,94,50,0.1); }
        .article-card .card-body { padding: 20px; }
        .article-card .card-img-top { height: 180px; object-fit: cover; }
    </style>
</head>
<body>
    @include('partials.public-navbar')

    <div class="page-header">
        <div class="container">
            <h1><i class="fa-solid fa-book-open me-2"></i>{{ __('article.title') }}</h1>
            <p class="opacity-90 mt-2">{{ __('article.subtitle') }}</p>
        </div>
    </div>

    <div class="container py-4">
        <div class="row g-4">
            @forelse ($articles as $article)
            <div class="col-md-6 col-lg-4">
                <a href="{{ route('articles.show', $article) }}" class="text-decoration-none text-dark">
                    <div class="article-card">
                        @if ($article->cover_url)
                            <img src="{{ $article->cover_url }}" alt="{{ $article->title }}" class="card-img-top">
                        @else
                            <div class="card-img-top d-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success">
                                <i class="fa-solid fa-newspaper" style="font-size:3rem;opacity:0.3;"></i>
                            </div>
                        @endif
                        <div class="card-body d-flex flex-column">
                            @if ($article->category)
                                <span class="badge bg-success bg-opacity-10 text-success align-self-start mb-2">{{ $article->category }}</span>
                            @endif
                            <h5 class="fw-bold mb-2">{{ $article->title }}</h5>
                            @if ($article->excerpt)
                                <p class="small text-muted flex-grow-1">{{ Str::limit($article->excerpt, 120) }}</p>
                            @endif
                            <div class="d-flex justify-content-between align-items-center mt-2 small text-muted">
                                <span><i class="fa-regular fa-user me-1"></i>{{ $article->author ?? __('article.no_author') }}</span>
                                <span>{{ $article->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @empty
            <div class="col-12 text-center py-5 text-muted">
                <i class="fa-regular fa-file-lines d-block fs-1 mb-3 opacity-50"></i>
                <p>{{ __('article.no_articles') }}</p>
            </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $articles->links() }}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
