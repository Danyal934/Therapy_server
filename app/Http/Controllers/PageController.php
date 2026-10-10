<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function home()
    {
        return view('pages.home');
    }

    public function about()
    {
        return view('pages.about');
    }

    public function privateTherapy()
    {
        return view('pages.private-therapy');
    }

    public function corporateServices()
    {
        return view('pages.corporate-services');
    }

    public function app()
    {
        return view('pages.app');
    }

    public function shop()
    {
        return view('pages.shop');
    }

    public function contact()
    {
        return view('pages.contact');
    }
}

