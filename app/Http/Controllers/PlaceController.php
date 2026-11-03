<?php

namespace App\Http\Controllers;

use App\Services\PlaceRepository;
use Illuminate\Http\Request;

class PlaceController extends Controller
{
    public function home()
    {
        $categories = PlaceRepository::getCategories();
        $campusPlaces = PlaceRepository::getCampusPlaces();
        $nearbyPlaces = PlaceRepository::getNearbyPlaces();
        $allPlaces = PlaceRepository::getAllPlaces();

        return view('pages.home', [
            'categories' => $categories,
            'campusPlaces' => $campusPlaces,
            'nearbyPlaces' => $nearbyPlaces,
            'allPlaces' => $allPlaces,
        ]);
    }

    public function explore(Request $request)
    {
        $categories = PlaceRepository::getCategories();
        $allPlaces = PlaceRepository::getAllPlaces();
        $selectedCategory = $request->query('category', 'all');
        $searchQuery = $request->query('q', '');
        $filterType = $request->query('type', 'all'); // 'all', 'campus', 'nearby'

        return view('pages.explore', [
            'categories' => $categories,
            'allPlaces' => $allPlaces,
            'initialCategory' => $selectedCategory,
            'initialSearch' => $searchQuery,
            'initialType' => $filterType,
        ]);
    }

    public function show(string $slug)
    {
        $place = PlaceRepository::findPlaceBySlug($slug);

        if (!$place) {
            abort(404, 'The place you are looking for was not found.');
        }

        $relatedPlaces = PlaceRepository::getRelatedPlaces($slug, 3);

        return view('pages.place-details', [
            'place' => $place,
            'relatedPlaces' => $relatedPlaces,
        ]);
    }

    public function contribute()
    {
        $categories = PlaceRepository::getCategories();

        return view('pages.contribute', [
            'categories' => $categories,
        ]);
    }

    public function about()
    {
        return view('pages.about');
    }
}
