<?php

namespace App\Http\Controllers;

class ImageController extends Controller
{

    /**
     * ImageController constructor.
     */
    public function __construct()
    {

        $this->middleware('auth');
        parent::__construct();

    }

    /**
     * Return Image
     *
     * @param $name
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function index($name) {

        return response()->file(storage_path('app/images/'.$name));

    }

}
