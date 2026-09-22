<!doctype html>
<html class="light" lang="en">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>{{ config('app.name', 'Laravel') }}</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&amp;display=swap" rel="stylesheet" />
  <link
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
    rel="stylesheet" />
  <script id="tailwind-config">
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "on-secondary-fixed": "#2f1500",
            "primary-fixed-dim": "#a9c7ff",
            "on-primary-container": "#8cb6ff",
            "error-container": "#ffdad6",
            "on-error-container": "#93000a",
            "on-secondary-container": "#603100",
            "surface-container-lowest": "#ffffff",
            "tertiary-fixed-dim": "#ffb691",
            "secondary-fixed-dim": "#ffb77d",
            "on-secondary": "#ffffff",
            "surface-container-highest": "#e0e3e5",
            "primary-fixed": "#d6e3ff",
            "tertiary-fixed": "#ffdbcb",
            "on-error": "#ffffff",
            "secondary-fixed": "#ffdcc3",
            "inverse-primary": "#a9c7ff",
            "on-tertiary": "#ffffff",
            "on-tertiary-fixed": "#341100",
            "surface-container-high": "#e6e8ea",
            tertiary: "#552000",
            "surface-bright": "#f8f9fb",
            "outline-variant": "#c2c6d2",
            primary: "#003063",
            "surface-container": "#eceef0",
            "on-surface-variant": "#424751",
            "secondary-container": "#fd8b00",
            "surface-dim": "#d8dadc",
            secondary: "#904d00",
            "surface-container-low": "#f2f4f6",
            "primary-container": "#00468c",
            surface: "#f8f9fb",
            "on-tertiary-container": "#ff9d69",
            "on-background": "#191c1e",
            "surface-variant": "#e0e3e5",
            "on-surface": "#191c1e",
            "on-secondary-fixed-variant": "#6e3900",
            "tertiary-container": "#793100",
            "on-tertiary-fixed-variant": "#793100",
            "on-primary": "#ffffff",
            error: "#ba1a1a",
            background: "#f8f9fb",
            outline: "#737782",
            "on-primary-fixed-variant": "#00468c",
            "surface-tint": "#2a5ea5",
            "inverse-on-surface": "#eff1f3",
            "inverse-surface": "#2d3133",
            "on-primary-fixed": "#001b3d",
          },
          borderRadius: {
            DEFAULT: "0.125rem",
            lg: "0.25rem",
            xl: "0.5rem",
            full: "0.75rem",
          },
          fontFamily: {
            headline: ["Inter"],
            body: ["Inter"],
            label: ["Inter"],
          },
        },
      },
    };
  </script>
  <style>
    .material-symbols-outlined {
      font-variation-settings:
        "FILL" 0,
        "wght" 400,
        "GRAD" 0,
        "opsz" 24;
    }

    body {
      font-family: "Inter", sans-serif;
      background-color: #f8f9fb;
    }

    .no-scrollbar::-webkit-scrollbar {
      display: none;
    }
  </style>
  <style>
    body {
      min-height: 100dvh;
      overflow-x: hidden;
    }

    *, *::before, *::after {
      box-sizing: border-box;
    }

    img {
      max-width: 100%;
    }

    @media (max-width: 640px) {
      body {
        font-size: 14px;
      }

      input,
      select,
      button {
        max-width: 100%;
      }

      .mobile-safe-bottom {
        padding-bottom: max(1rem, env(safe-area-inset-bottom));
      }
    }
  </style>
</head>

<body class="@yield('bodyClass', 'bg-surface text-on-surface min-h-screen')">
  @yield('content')
  @auth
  <script>
    (function () {
      var idleLimit = 10 * 60 * 1000;
      var heartbeatInterval = 4 * 60 * 1000;
      var lastActivity = Date.now();
      var lastHeartbeat = Date.now();
      var csrfToken = document.querySelector('meta[name="csrf-token"]').content;
      var activityEvents = ['click', 'keydown', 'mousemove', 'scroll', 'touchstart'];

      function markActivity() {
        lastActivity = Date.now();
      }

      activityEvents.forEach(function (eventName) {
        document.addEventListener(eventName, markActivity, { passive: true });
      });

      window.setInterval(function () {
        var idleTime = Date.now() - lastActivity;
        if (idleTime >= idleLimit) {
          fetch('{{ route('logout') }}', {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': csrfToken,
              'Accept': 'application/json'
            },
            credentials: 'same-origin'
          }).finally(function () {
            window.location.replace('{{ route('landing') }}');
          });
          return;
        }

        if (Date.now() - lastHeartbeat >= heartbeatInterval && lastActivity > lastHeartbeat) {
          fetch('{{ route('session.heartbeat') }}', {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': csrfToken,
              'Accept': 'application/json'
            },
            credentials: 'same-origin'
          }).then(function () {
            lastHeartbeat = Date.now();
          }).catch(function () {});
        }
      }, 30000);
    })();
  </script>
  @endauth
</body>

</html>
