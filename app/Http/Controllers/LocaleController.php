<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class LocaleController extends Controller
{
    public function switch(string $locale)
    {
        Log::info('Locale route reached', [
            'locale_param' => $locale,
            'method' => request()->method(),
            'full_url' => request()->fullUrl(),
            'session_id' => session()->getId(),
            'session_locale_before' => session('locale'),
            'session_intended_before' => session()->get('url.intended'),
            'referer' => request()->headers->get('referer'),
        ]);

        if (!in_array($locale, ['id', 'en'])) {
            $locale = 'id';
        }

        session()->put('locale', $locale);
        App::setLocale($locale);

        $target = url()->previous();
        $intended = session()->get('url.intended');
        $referer = request()->headers->get('referer');

        Log::info('Locale route redirecting', [
            'target' => $target,
            'session_intended' => $intended,
            'referer' => $referer,
            'locale_set' => $locale,
        ]);

        if ($referer === null && $intended === null) {
            Log::warning('Locale route: no referer and no intended URL, redirecting to /');
            return redirect('/');
        }

        return back();
    }
}
