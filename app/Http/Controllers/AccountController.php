<?php

namespace App\Http\Controllers;

use App\Events\AccountDeleted;
use App\Events\InvitationSaved;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AccountController extends Controller
{

    /**
     * AccountController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show account overview
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index() {

        return view('account.index');

    }

    /**
     * Delete user account
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws ValidationException
     */
    public function destroy(Request $request) {

        $request->validate(['password' => 'required']);
        $user = auth()->user();

        if(!$user->checkPassword($request->post('password')))
            throw ValidationException::withMessages(['password' => 'Password value is incorrect']);

        event(new AccountDeleted($user->name, $user->email));

        $user->delete();
        auth()->guard()->logout();

        return $request->wantsJson() ?
            new JsonResponse([], 200) :
            redirect(route('login'))->with([
                'message' => 'Account permanently deleted.',
                'success' => true,
            ]);

    }

    /**
     * Check password for DeleteAccountForm.vue
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function checkPassword(Request $request) {

        $request->validate(['password' => 'required']);

        return new JsonResponse(
            ['success' => auth()->user()->checkPassword($request->post('password'))]
            , 200);

    }


}
