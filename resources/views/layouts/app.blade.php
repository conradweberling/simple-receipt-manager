<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="robots" content="noindex">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">

        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">

                @auth
                <button class="btn btn-toggle-nav pb-0" v-b-toggle.sidebar-nav>
                    <b-icon icon="list" font-scale="1.9"></b-icon>
                </button>
                @endauth

                <a class="navbar-brand mx-auto text-center" href="{{ url('/') }}">
                    {{ config('app.name') }}
                </a>

                @auth
                <button
                    class="btn btn-toggle-nav"
                    @click="newNotification=false"
                    v-b-toggle.sidebar-notifications
                >

                    <notification-icon
                        :default="{{auth()->user()->unreadNotifications->count() ? 'true' : 'false'}}"
                        :show="newNotification"
                    ></notification-icon>

                </button>
                @endauth

            </div>
        </nav>

        @auth

        <b-sidebar id="sidebar-nav" title="Menu" width="600px" backdrop>

            <div class="px-3 py-2">
                <b-navbar>
                    <b-navbar-nav vertical>
                        <div>

                            <b-nav-item href="{{route('home')}}" {{ request()->is('/') ? 'active' : '' }}>
                                Home
                            </b-nav-item>

                            <b-nav-item href="{{route('receipts')}}" {{ request()->is('receipts') ? 'active' : '' }}>
                                Receipts
                            </b-nav-item>

                            <b-nav-item href="{{route('account')}}" {{ request()->is('account') ? 'active' : '' }}>
                                Account
                            </b-nav-item>

                            <b-nav-item href="{{route('invitations')}}" {{ request()->is('invitations') ? 'active' : '' }}>
                                Invitations
                            </b-nav-item>

                            <b-nav-item onclick="document.getElementById('logout-form').submit();">
                                Logout
                            </b-nav-item>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </b-navbar-nav>
                </b-navbar>
            </div>

        </b-sidebar>


        <b-sidebar
            id="sidebar-notifications"
            title="Notifications"
            width="600px"
            backdrop
            right
            @shown="notificationSidebarShown()"
            @hidden="notificationSidebarHidden()"
        >

            <div class="px-3 py-2">

                <notification-list
                    route="{{route('notifications')}}"
                    update="{{route('notifications.update')}}"
                    destroy="{{route('notifications.destroy', ['notification' => 'replaceid'])}}"
                    :open="visibleNotificationSidebar"
                ></notification-list>

            </div>

        </b-sidebar>

        @endauth

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
