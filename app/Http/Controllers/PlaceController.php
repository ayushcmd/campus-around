<?php

namespace App\Http\Controllers;

use App\Services\PlaceRepository;
use Illuminate\Http\Request;

class PlaceController extends Controller
{
    public function home()
    {
        $insidePlaces = PlaceRepository::getInsidePlaces();
        $outsidePlaces = PlaceRepository::getOutsidePlaces();
        $allPlaces = PlaceRepository::getAllPlaces();
        $categories = PlaceRepository::getCategories();

        return view('pages.home', [
            'categories' => $categories,
            'campusPlaces' => $insidePlaces,
            'nearbyPlaces' => $outsidePlaces,
            'allPlaces' => $allPlaces,
        ]);
    }

    public function inside(Request $request)
    {
        $categories = PlaceRepository::getInsideCategories();
        $places = PlaceRepository::getInsidePlaces();
        $selectedCategory = $request->query('category', 'all');
        $searchQuery = $request->query('q', '');

        return view('pages.inside', [
            'categories' => $categories,
            'places' => $places,
            'initialCategory' => $selectedCategory,
            'initialSearch' => $searchQuery,
        ]);
    }

    public function outside(Request $request)
    {
        $categories = PlaceRepository::getOutsideCategories();
        $places = PlaceRepository::getOutsidePlaces();
        $selectedCategory = $request->query('category', 'all');
        $searchQuery = $request->query('q', '');

        return view('pages.outside', [
            'categories' => $categories,
            'places' => $places,
            'initialCategory' => $selectedCategory,
            'initialSearch' => $searchQuery,
        ]);
    }

    public function locate(Request $request)
    {
        $insidePlaces = PlaceRepository::getInsidePlaces();
        $outsidePlaces = PlaceRepository::getOutsidePlaces();
        $allPlaces = PlaceRepository::getAllPlaces();

        // Optional pre-selected start or destination slug/id
        $startSlug = $request->query('start');
        $destSlug = $request->query('destination');

        $initialStart = $startSlug ? PlaceRepository::findPlaceBySlug($startSlug) : null;
        $initialDest = $destSlug ? PlaceRepository::findPlaceBySlug($destSlug) : null;

        return view('pages.locate', [
            'insidePlaces' => $insidePlaces,
            'outsidePlaces' => $outsidePlaces,
            'allPlaces' => $allPlaces,
            'initialStart' => $initialStart,
            'initialDest' => $initialDest,
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

    public function contribute(Request $request)
    {
        $categories = PlaceRepository::getCategories();
        $placeSlug = $request->query('place');
        $action = $request->query('action', 'details');
        $selectedPlace = $placeSlug ? PlaceRepository::findPlaceBySlug($placeSlug) : null;
        $allPlaces = PlaceRepository::getAllPlaces();

        return view('pages.contribute', [
            'categories' => $categories,
            'selectedPlace' => $selectedPlace,
            'action' => $action,
            'allPlaces' => $allPlaces,
        ]);
    }

    public function contact(Request $request)
    {
        $allPlaces = PlaceRepository::getAllPlaces();
        $placeSlug = $request->query('place');
        $selectedPlace = $placeSlug ? PlaceRepository::findPlaceBySlug($placeSlug) : null;
        $type = $request->query('type', 'feedback');

        return view('pages.contact', [
            'allPlaces' => $allPlaces,
            'selectedPlace' => $selectedPlace,
            'initialType' => $type,
        ]);
    }

    public function about()
    {
        return view('pages.about');
    }
}
