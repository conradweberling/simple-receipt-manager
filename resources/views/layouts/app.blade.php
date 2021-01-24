<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    <!-- Charset -->
    <meta charset="utf-8">

    <!-- No index -->
    <meta name="robots" content="noindex">

    <!-- Mobile -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">

    <!-- Web Application Manifest -->
    <link rel="manifest" href="{{ route('laravelpwa.manifest') }}">

    <!-- Chrome for Android theme color -->
    <meta name="theme-color" content="{{ config('laravelpwa.theme_color') }}">

    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="{{ config('laravelpwa.display') == 'standalone' ? 'yes' : 'no' }}">
    <meta name="application-name" content="{{ config('laravelpwa.short_name') }}">
    <link rel="icon" sizes="512x512" href="{{ asset('/images/icons/receipt-512x512.png') }}">

    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="{{ config('laravelpwa.display') == 'standalone' ? 'yes' : 'no' }}">
    <meta name="apple-mobile-web-app-status-bar-style" content="{{  config('laravelpwa.status_bar') }}">
    <meta name="apple-mobile-web-app-title" content="{{ config('laravelpwa.short_name') }}">
    <link rel="apple-touch-icon" href="{{ asset('/images/icons/receipt-512x512.png') }}">

    <!-- Title -->
    <title>{{ config('app.name') }}</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Register SW -->
    <script type="text/javascript">
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('{{ url('/sw.js') }}', {
                scope: '{{ url('/') }}/'
            }).then(function (registration) {
                console.log('PWA: ServiceWorker registration successful with scope: ', registration.scope);
            }, function (err) {
                console.log('PWA: ServiceWorker registration failed: ', err);
            });
        }
    </script>

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

                <b-overlay :show="loadingNav">

                    <b-navbar>
                        <b-navbar-nav vertical>
                            <div>

                                <b-nav-item
                                    href="{{route('home')}}"
                                    {{ request()->is('/') ? 'active' : '' }}
                                    @click="clickNavItem()"
                                >
                                    Home
                                </b-nav-item>

                                <b-nav-item
                                    href="{{route('receipts')}}"
                                    {{ request()->is('receipts') ? 'active' : '' }}
                                    @click="clickNavItem()"
                                >
                                    Receipts
                                </b-nav-item>

                                <b-nav-item
                                    href="{{route('account')}}"
                                    {{ request()->is('account') ? 'active' : '' }}
                                    @click="clickNavItem()"
                                >
                                    Account
                                </b-nav-item>

                                <b-nav-item
                                    href="{{route('invitations')}}"
                                    {{ request()->is('invitations') ? 'active' : '' }}
                                    @click="clickNavItem()"
                                >
                                    Invitations
                                </b-nav-item>

                                <b-nav-item
                                    onclick="document.getElementById('logout-form').submit();"
                                    @click="clickNavItem()"
                                >
                                    Logout
                                </b-nav-item>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </b-navbar-nav>
                    </b-navbar>

                </b-overlay>

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
