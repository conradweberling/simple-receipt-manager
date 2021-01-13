<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ReceiptController extends Controller
{
    /**
     * ReceiptController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     *
     */
    public function index()
    {
        return view('receipt.index');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validateForm($request);
        $image_path = request('image')->store('images');

        Receipt::create([
            'image' => $image_path,
            'thumbnail' => $this->createThumbByImage($image_path),
            'date' => $request->post('date'),
            'amount' => $request->post('amount')
        ]);

        return ($request->wantsJson()) ?
            new JsonResponse([], 200) :
            redirect(route('receipts'))->with([
                'message' => 'Receipt created',
                'success' => true
            ]);

    }

    /**
     * Validation
     *
     * @param Request $request
     */
    protected function validateForm(Request $request) {

        $request->validate([
            'image' => 'required|mimes:jpeg,png',
            'date'  => 'required|date',
            'amount' => 'required'
        ]);

    }

    /**
     * Generate Thumbnail
     *
     * @param $image_path
     * @return string
     */
    protected function createThumbByImage($image_path) {

        $thumb = Image::make(storage_path('app/'.$image_path));
        $thumb->crop(500, 500);
        $thumb_path = 'images/'.$thumb->filename.'.thumbnail.'.$thumb->extension;
        $thumb->save(storage_path('app/'.$thumb_path));

        return $thumb_path;

    }

}
