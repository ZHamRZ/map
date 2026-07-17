<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('seo.faq_title') }}</title>
    <meta name="description" content="{{ __('seo.faq_desc') }}">
    <meta property="og:title" content="{{ __('seo.faq_title') }}">
    <meta property="og:description" content="{{ __('seo.faq_desc') }}">
    <meta property="og:type" content="website">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f4fcf6; color: #2b3d32; }
        .navbar { box-shadow: 0 2px 12px rgba(0,0,0,0.1); z-index: 2000; }
        .page-header {
            background: linear-gradient(135deg, #0b2e1b 0%, #1b5e20 50%, #2e7d32 100%);
            color: white; padding: 50px 0 40px; text-align: center;
        }
        .page-header h1 { font-weight: 800; font-size: 2rem; }
        .faq-section { margin-bottom: 2rem; }
        .faq-section h5 {
            font-weight: 700; color: #0b2e1b;
            border-bottom: 3px solid #4caf50; padding-bottom: 8px; margin-bottom: 16px;
        }
        .accordion-button:not(.collapsed) {
            background: #e8f5e9; color: #0b2e1b; font-weight: 600;
        }
        .accordion-button:focus { box-shadow: none; border-color: #4caf50; }
    </style>
</head>
<body>
    @include('partials.public-navbar')

    <div class="page-header">
        <div class="container">
            <h1><i class="fa-solid fa-circle-question me-2"></i>{{ __('faq.title') }}</h1>
            <p class="opacity-90 mt-2">{{ __('faq.subtitle') }}</p>
        </div>
    </div>

    <div class="container py-4" style="max-width:800px;">
        @forelse ($faqs as $category => $items)
            <div class="faq-section">
                <h5><i class="fa-solid fa-tag me-2 text-success"></i>{{ $category ?: 'Umum' }}</h5>
                <div class="accordion" id="faq-{{ Str::slug($category ?: 'umum') }}">
                    @foreach ($items as $faq)
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#faq-{{ $faq->id }}">
                                {{ $faq->question }}
                            </button>
                        </h2>
                        <div id="faq-{{ $faq->id }}" class="accordion-collapse collapse"
                             data-bs-parent="#faq-{{ Str::slug($category ?: 'umum') }}">
                            <div class="accordion-body">
                                {{ $faq->answer }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="text-center py-5 text-muted">
                <i class="fa-regular fa-circle-question d-block fs-1 mb-3 opacity-50"></i>
                <p>{{ __('faq.no_faqs') }}</p>
            </div>
        @endforelse
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
