<?php

namespace App\Http\Controllers;

use App\Events\InvitationSaved;
use App\Models\Invitation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InvitationController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request) {



        return ($request->wantsJson()) ?
            new JsonResponse([], 200) :
            view('invitation.index');

    }

    public function create()
    {
        return redirect(route('invitations'));
    }

    public function store(Request $request)
    {

        $this->validateInvitation();

        $token = Str::random(60);

        //TODO check if exists

        $invitation = new Invitation();
        $invitation->email = $request->get('email');
        $invitation->user_id = auth()->user()->id;
        $invitation->token = $token;
        $saved = $invitation->save();

        if($saved) {
            $with = [
                'status' => __('Invitation sent to ').request('email'),
                'success' => true
            ];
            event(new InvitationSaved($request->get('email'), $token));
        } else {
            $with = [
                'status' => __('Invitation could not be sent'),
                'success' => false
            ];
        }

        return ($request->wantsJson()) ?
            new JsonResponse([], ($saved) ? 200 : 500) :
            redirect(route('invitations'))->with($with);

    }

    protected function validateInvitation() {

        return request()->validate([
            'email' => 'required|email'
        ]);

    }



}
