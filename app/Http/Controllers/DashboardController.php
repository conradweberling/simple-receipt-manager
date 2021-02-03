<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use App\Models\User;
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
        $perPage = ($request->get('perPage')) ?: 3;

        $result->total = Receipt::monthCount();
        $months = Receipt::months(
            ($page != 1) ? ($page*$perPage)-$perPage : 0,
            $perPage
        );

        $users = User::select('id')->get()->pluck('id')->toArray();
        foreach ($months as $month) {

            $obj = (/*cache($month)*/false) ?: $this->getMonthObjFromDb($month, $users);

            $result->data[] = $obj;

        }

        return new JsonResponse($result, 200);

    }

    /**
     * Get chart obj from db
     *
     * @param $month
     * @param $users
     * @return stdClass
     * @throws \Exception
     */
    protected function getMonthObjFromDb($month, $users)
    {

        $obj = new StdClass();

        $obj->month = date("F Y", strtotime($month));
        $obj->total = Receipt::sumAmountByMonth($month);

        $obj->amounts = [];
        $obj->names = [];
        $obj->colors = [];

        foreach(Receipt::sumUserAmountByMonth($month, $users) as $user) {
            array_push($obj->amounts, $user['sum']);
            array_push($obj->names, $user['name']);
            array_push($obj->colors, $user['color']);
        }

        $obj->payments = Receipt::calcPayments($obj->total, $obj->amounts, $obj->names);

        cache([$month => $obj], now()->addYear());

        return $obj;

    }

}
