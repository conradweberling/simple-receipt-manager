<?php

namespace App\Http\Controllers;

use App\Events\AccountDeleted;
use App\Models\User;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AccountController extends Controller
{

    use ResetsPasswords;

    /**
     * AccountController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        parent::__construct();
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

        auth()->guard()->logout();
        $user->delete();

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

    /**
     * Update password
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updatePassword(Request $request)
    {

        $request->validate([
            'password' => 'required|password',
            'new_password' => 'required|min:8|confirmed',
            'new_password_confirmation' => 'required'
        ]);

        $this->resetPassword($request->user(), $request->post('new_password'));

        return new JsonResponse([
                'success' => true
            ], 200);

    }


    /**
     * Update email
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateEmail(Request $request)
    {

        $request->validate([
            'email' => 'required|email|confirmed|unique:users',
            'email_confirmation' => 'required'
        ]);

        $request->user()->email = $request->post('email');
        $request->user()->save();

        return new JsonResponse([
            'success' => true
        ], 200);

    }


    /**
     * Check email
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function checkEmail(Request $request)
    {

        $request->validate(['email' => 'required',]);

        return new JsonResponse([
            'exists' => (User::where('email', '=', $request->post('email'))->get()->isEmpty()) ? false : true
        ], 200);

    }

}
