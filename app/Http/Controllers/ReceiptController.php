<?php

namespace App\Http\Controllers;

use App\Events\ReceiptDestroyed;
use App\Models\Receipt;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class ReceiptController extends Controller
{
    /**
     * ReceiptController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        parent::__construct();
    }

    /**
     * Index
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {

        return $request->wantsJson() ?
            Receipt::paginateAndSearch($request->get('q')) :
            view('receipt.index');

    }


    /**
     * Create form
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create() {

        return view('receipt.create');

    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validateForm($request);

        $image_path = $this->storeImage($request);

        Receipt::create([
            'user_id' => $request->user()->id,
            'image' => $image_path,
            'thumbnail' => $this->createThumbByImage($image_path),
            'date' => $request->post('date'),
            'amount' => $request->post('amount')
        ]);

        return ($request->wantsJson()) ?
            new JsonResponse([], 200) :
            redirect(route('receipts'))->with([
                'message' => 'Receipt created.',
                'success' => true
            ]);

    }

    /**
     * Destroy receipt
     *
     * @param Receipt $receipt
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(Receipt $receipt, Request $request) {

        $deleted = $receipt->delete();

        if($deleted) event(new ReceiptDestroyed($receipt->user_id, $receipt->date, $receipt->amount));

        return ($request->wantsJson()) ?
            new JsonResponse([], ($deleted) ? 200 : 500) :
            redirect(route('receipts'))->with([
                'message' => 'Receipt '.(($deleted) ? 'permanently ' : 'can\'t').' deleted.',
                'success' => $deleted ? true : false
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
            'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/'
        ]);

    }

    /**
     * Store image
     *
     * @param Request $request
     * @return string
     */
    protected function storeImage(Request $request) {

        $image = Image::make($request->file('image')->getRealpath());
        $image->orientate();
        $image_ext = request('image')->getClientOriginalExtension();
        $image_sub_path = 'images/'.uniqid().'.'.$image_ext;
        $image_path = storage_path('app/'.$image_sub_path);
        $image->save($image_path);

        return $image_sub_path;

    }

    /**
     * Generate Thumbnail
     *
     * @param $image_path
     * @return string
     */
    protected function createThumbByImage($image_path) {

        $thumb = Image::make(storage_path('app/'.$image_path));
        $thumb->fit(config('view.thumbnail_width'), config('view.thumbnail_height'));
        $thumb_path = 'images/'.$thumb->filename.'.thumbnail.'.$thumb->extension;
        $thumb->save(storage_path('app/'.$thumb_path));

        return $thumb_path;

    }

}
