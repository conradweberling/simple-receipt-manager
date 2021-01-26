<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use stdClass;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        parent::__construct();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('dashboard');
    }

    /**
     * Get dashboard / chart data
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function chart(Request $request)
    {

        $result = new StdClass;

        $request->validate([
            'page' => 'numeric',
            'perPage' => 'numeric'
        ]);

        $page = ($request->get('page')) ?: 1;
        $perPage = ($request->get('perPage')) ?: 6;

        $result->total = Receipt::monthCount();
        $months = Receipt::months(
            ($page != 1) ? ($page*$perPage)-$perPage : 0,
            $perPage
        );

        foreach ($months as $month) {

            $obj = (cache($month)) ?: $this->getMonthObjFromDb($month);
            $result->data[] = $obj;

        }

        return new JsonResponse($result, 200);

    }

    /**
     * Get chart obj from db
     *
     * @param $month
     * @return stdClass
     * @throws \Exception
     */
    protected function getMonthObjFromDb($month)
    {

        $obj = new StdClass();

        $obj->month = date("F Y", strtotime($month));
        $obj->total = Receipt::sumAmountByMonth($month);

        $obj->amounts = [];
        $obj->names = [];
        $obj->colors = [];

        foreach(Receipt::sumUserAmountByMonth($month) as $sum) {
            array_push($obj->amounts, $sum['sum']);
            array_push($obj->names, $sum['name']);
            array_push($obj->colors, $sum['color']);
        }

        cache([$month => $obj], now()->addYear());

        return $obj;

    }

}
