<?php

namespace App\Http\Controllers;

use App\Events\ReceiptCreated;
use App\Events\ReceiptDeleted;
use App\Models\Receipt;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

        Receipt::create([
            'user_id' => $request->user()->id,
            'image' => $request->post('image'),
            'thumbnail' => $request->post('thumbnail'),
            'date' => $request->post('date'),
            'amount' => $request->post('amount')
        ]);

        event(new ReceiptCreated($request->user()->id, $request->post('date'), $request->post('amount')));

        return ($request->wantsJson()) ?
            new JsonResponse([], 200) :
            redirect(route('receipts'))->with([
                'message' => 'Receipt created.',
                'success' => true
            ]);

    }

    /**
     * Delete receipt
     *
     * @param Receipt $receipt
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function delete(Receipt $receipt, Request $request)
    {

        $deleted = $receipt->delete();

        if($deleted) event(new ReceiptDeleted($receipt->user_id, $receipt->date, $receipt->amount));

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
    protected function validateForm(Request $request)
    {

        $request->validate([
            'image' => 'required',
            'thumbnail' => 'required',
            'date'  => 'required|date',
            'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/'
        ]);

    }


}
