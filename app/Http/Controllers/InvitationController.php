<?php

namespace App\Http\Controllers;

use App\Events\InvitationSaved;
use App\Models\Invitation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class InvitationController extends Controller
{

    /**
     * InvitationController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show all invitations and create view
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|JsonResponse
     */
    public function index(Request $request) {

        //TODO Show Invitations

        return ($request->wantsJson()) ?
            new JsonResponse([], 200) :
            view('invitation.index');

    }

    /**
     * Redirect to Index
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function create()
    {
        return redirect(route('invitations'));
    }

    /**
     * Store new or call update
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {

        $this->validateInvitation();

        if($invitation = Invitation::find($request->get('email'))) return $this->update($request, $invitation);

        $token = Str::random(60);

        $invitation = new Invitation();
        $invitation->email = $request->get('email');
        $invitation->user_id = auth()->user()->id;
        $invitation->token = $token;
        $saved = $invitation->save();

        if($saved) event(new InvitationSaved($request->get('email'), $token));

        return $this->getResult($request, $saved);

    }

    /**
     * Update
     *
     * @param Request $request
     * @param Invitation $invitation
     * @return \Illuminate\Contracts\Foundation\Application|JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, Invitation $invitation) {

        if(($minutes = Carbon::now()->diffInMinutes($invitation->updated_at)) < config('mail.invitation_timeout'))
            return $this->timeout($request, $minutes);

        $updated = $invitation->touch(); //update ts

        event(new InvitationSaved($request->get('email'), $invitation->token));

        return $this->getResult($request, $updated, true);

    }

    /**
     * Prevent spam by timeout
     *
     * @param Request $request
     * @param $minutes
     * @return \Illuminate\Contracts\Foundation\Application|JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function timeout(Request $request, $minutes) {

        return ($request->wantsJson()) ?
            new JsonResponse([], 429) :
            redirect(route('invitations'))->with([
                'message' =>    'Invitation could not be sent because an invitation was sent a short time ago. '.
                                'In '.(config('mail.invitation_timeout') - $minutes).' minutes the invitation can be sent to '.
                                $request->get('email').' again.',
                'success' => false
            ]);

    }

    /**
     * Result
     *
     * @param Request $request
     * @param $success
     * @param false $update
     * @return \Illuminate\Contracts\Foundation\Application|JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function getResult(Request $request, $success, $update=false) {

        return ($request->wantsJson()) ?
            new JsonResponse([], ($success) ? 200 : 500) :
            redirect(route('invitations'))->with([
                'message' => ($success) ?
                    __('Invitation sent to ').$request->get('email').($update ? __(' again.') : '') :
                    'Invitation could not be sent',
                'success' => $success
            ]);

    }

    /**
     * Validation
     *
     * @return array
     */
    protected function validateInvitation() {

        return request()->validate([
            'email' => 'required|email'
        ]);

    }

}
