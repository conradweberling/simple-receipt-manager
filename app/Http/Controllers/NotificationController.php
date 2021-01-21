<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{

    /**
     * NotificationController constructor.
     */
    public function __construct()
    {

        $this->middleware('auth');
        parent::__construct();

    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function index(Request $request)
    {

        return ($request->wantsJson()) ?
            new JsonResponse([$request->user()->notifications], 200) :
            redirect(route('home'));

    }

    /**
     * Mark user notifications as read
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request) {

        $request->user()->notifications->markAsRead();

        return ($request->wantsJson()) ?
            new JsonResponse([], 200) :
            redirect(route('home'));

    }

    /**
     * Delete
     *
     * @param Notification $notification
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(Notification $notification, Request $request) {

        return ($request->wantsJson()) ?
            new JsonResponse(['success' => $notification->delete()], 200) :
            redirect(route('home'));

    }
}

