<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category');

        $query = Event::published();

        if ($category && in_array($category, ['desa', 'budaya'])) {
            $query->where('category', $category);
        }

        $upcoming = (clone $query)->upcoming()->get();
        $past = (clone $query)->past()->get();

        return view('events.index', compact('upcoming', 'past', 'category'));
    }

    public function show(Event $event)
    {
        if (!$event->is_published) {
            abort(404);
        }

        return view('events.show', compact('event'));
    }
}
