<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $article->title }} — {{ __('seo.articles_title') }}</title>
    <meta name="description" content="{{ $article->excerpt ?? Str::limit($article->body, 160) }}">
    <meta property="og:title" content="{{ $article->title }}">
    <meta property="og:description" content="{{ $article->excerpt ?? Str::limit(strip_tags($article->body), 160) }}">
    @if ($article->cover_url)
        <meta property="og:image" content="{{ url($article->cover_url) }}">
    @endif
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:card" content="summary_large_image">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8faf8; color: #2b3d32; }
        .navbar { box-shadow: 0 2px 12px rgba(0,0,0,0.1); z-index: 2000; }
        .article-cover {
            width: 100%; height: 380px; object-fit: cover;
            border-radius: 20px; box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        }
        .article-body { font-size: 1.05rem; line-height: 1.9; color: #3d4d44; }
        .article-body p { margin-bottom: 1.2rem; }
    </style>
</head>
<body>
    @include('partials.public-navbar')

    <div class="container py-4" style="max-width:800px;">
        <a href="{{ route('articles.index') }}" class="btn btn-sm btn-outline-success mb-3">
            <i class="fa-solid fa-arrow-left me-1"></i>{{ __('article.back') }}
        </a>

        @if ($article->cover_url)
            <img src="{{ $article->cover_url }}" alt="{{ $article->title }}" class="article-cover w-100 mb-4">
        @endif

        <div class="mb-3">
            @if ($article->category)
                <span class="badge bg-success bg-opacity-10 text-success me-2">{{ $article->category }}</span>
            @endif
            <span class="text-muted small">{{ $article->created_at->format('d M Y') }}</span>
            @if ($article->author)
                <span class="text-muted small ms-2"><i class="fa-regular fa-user me-1"></i>{{ $article->author }}</span>
            @endif
        </div>

        <h1 class="fw-800 mb-4" style="font-size:2rem;">{{ $article->title }}</h1>

        @if ($article->excerpt)
            <p class="lead text-muted mb-4 fst-italic">{{ $article->excerpt }}</p>
        @endif

        <div class="article-body">
            {!! nl2br(e($article->body)) !!}
        </div>

        <hr class="my-5">

        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('articles.index') }}" class="btn btn-outline-success">
                <i class="fa-solid fa-arrow-left me-1"></i>{{ __('article.back') }}
            </a>
            <div class="d-flex gap-2">
                <a href="https://www.facebook.com/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fa-brands fa-facebook"></i></a>
                <a href="https://wa.me/?text={{ urlencode($article->title . ' - ' . url()->current()) }}" target="_blank" class="btn btn-sm btn-outline-success"><i class="fa-brands fa-whatsapp"></i></a>
                <a href="https://twitter.com/intent/tweet?text={{ urlencode($article->title) }}&url={{ urlencode(url()->current()) }}" target="_blank" class="btn btn-sm btn-outline-dark"><i class="fa-brands fa-x-twitter"></i></a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
