<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

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

    /**
     * Store image
     *
     * @param Request $request
     * @return JsonResponse
     */
    protected function store(Request $request)
    {

        $request->validate([
            'image' => 'required|mimes:jpeg,png'
        ]);

        $image = Image::make($request->file('image')->getRealpath());
        $image->orientate();
        $image_ext = request('image')->getClientOriginalExtension();
        $image_sub_path = 'images/'.uniqid().'.'.$image_ext;
        $image_path = storage_path('app/'.$image_sub_path);
        $image->save($image_path);

        $thumbnail_sub_path = $this->generateThumbByImage($image_path);

        return new JsonResponse(['image' => $image_sub_path, 'thumbnail' => $thumbnail_sub_path], 200);

    }

    /**
     * Generate Thumbnail
     *
     * @param $image_path
     * @return string
     */
    protected function generateThumbByImage($image_path)
    {

        $thumb = Image::make($image_path);
        $thumb->fit(config('view.thumbnail_width'), config('view.thumbnail_height'));
        $thumb_sub_path = 'images/'.$thumb->filename.'.thumbnail.'.$thumb->extension;
        $thumb->save(storage_path('app/'.$thumb_sub_path));

        return $thumb_sub_path;

    }

}
