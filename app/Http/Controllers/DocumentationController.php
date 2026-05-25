<?php

namespace App\Http\Controllers;

class DocumentationController extends Controller
{
    public function index()
    {
        return view('docs.index');
    }

    public function gettingStarted()
    {
        return view('docs.getting-started');
    }

    public function projectFlow()
    {
        return view('docs.project-flow');
    }

    public function businessGuide()
    {
        return view('docs.business-guide');
    }

    public function modules()
    {
        return view('docs.modules');
    }
}
