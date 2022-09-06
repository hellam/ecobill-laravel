<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->
<head>
    <base href="../../../">
    <title>{{__('messages.app_name')}} - @yield('title')</title>
    <meta charset="utf-8"/>
    <meta name="description"
          content="The most advanced Bootstrap Admin Theme on Themeforest trusted by 100,000 beginners and professionals. Multi-demo, Dark Mode, RTL support and complete React, Angular, Vue, Asp.Net Core, Blazor, Django, Flask &amp; Laravel versions. Grab your copy now and get life-time updates for free."/>
    <meta name="keywords"
          content="Metronic, Bootstrap, Bootstrap 5, Angular, VueJs, React, Asp.Net Core, Blazor, Django, Flask &amp; Laravel, admin themes, web design, figma, web development, free templates, free admin themes, bootstrap theme, bootstrap template, bootstrap dashboard, bootstrap dak mode, bootstrap button, bootstrap datepicker, bootstrap timepicker, fullcalendar, datatables, flaticon"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta property="og:locale" content="en_US"/>
    <meta property="og:type" content="article"/>
    <meta property="og:title"
          content="Metronic - Bootstrap 5 HTML, VueJS, React, Angular, Asp.Net Core, Blazor, Django, Flask &amp; Laravel Admin Dashboard Theme"/>
    <meta property="og:url" content="https://keenthemes.com/metronic"/>
    <meta property="og:site_name" content="Keenthemes | Metronic"/>
    <link rel="canonical" href="https://preview.keenthemes.com/metronic8"/>
    <link rel="shortcut icon" href="{{ asset('assets/media/logos/favicon.png') }}"/>
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700"/>
    <!--end::Fonts-->
    <!--begin::Global Stylesheets Bundle(used by all pages)-->
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css"/>
    <!--end::Global Stylesheets Bundle-->
</head>
<!--end::Head-->
<!--begin::Body-->
<body data-kt-name="metronic" id="kt_body" class="app-blank bgi-size-cover bgi-position-center bgi-no-repeat">
<!--begin::Theme mode setup on page load-->
<script>if (document.documentElement) {
        const defaultThemeMode = "system";
        const name = document.body.getAttribute("data-kt-name");
        let themeMode = localStorage.getItem("kt_" + (name !== null ? name + "_" : "") + "theme_mode_value");
        if (themeMode === null) {
            if (defaultThemeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            } else {
                themeMode = defaultThemeMode;
            }
        }
        document.documentElement.setAttribute("data-theme", themeMode);
    }</script>
<!--end::Theme mode setup on page load-->
<!--begin::Main-->
<!--begin::Root-->
<div class="d-flex flex-column flex-root">
    <!--begin::Page bg image-->
    <style>
        body {
            background-image: url('{{ asset('assets/media/auth/bg10.jpeg') }}');
        }

        [data-theme="dark"] body {
            background-image: url('{{ asset('assets/media/auth/bg10-dark.jpeg') }}');
        }
    </style>
    <!--end::Page bg image-->
    <!--begin::Authentication - Sign-in -->
    <div class="d-flex flex-column flex-lg-row flex-column-fluid">
        <!--begin::Aside-->
        <div class="d-flex flex-lg-row-fluid">
            <!--begin::Content-->
            <div class="d-flex flex-column flex-center pb-0 pb-lg-10 p-10 w-100">
                <!--begin::Image-->
                <img class="theme-light-show mx-auto mw-100 w-150px w-lg-300px mb-10 mb-lg-20"
                     src="{{ asset('assets/media/auth/agency.png') }}" alt=""/>
                <img class="theme-dark-show mx-auto mw-100 w-150px w-lg-300px mb-10 mb-lg-20"
                     src="{{ asset('assets/media/auth/agency-dark.png') }}" alt=""/>
                <!--end::Image-->
                <!--begin::Title-->
                <h1 class="text-gray-800 fs-2qx fw-bold text-center mb-7">Fast, Efficient and Productive</h1>
                <!--end::Title-->
                <!--begin::Text-->
                <div class="text-gray-600 fs-base text-center fw-semibold">In this kind of post,
                    <a href="#" class="opacity-75-hover text-primary me-1">the blogger</a>introduces a person they’ve
                    interviewed
                    <br/>and provides some background information about
                    <a href="#" class="opacity-75-hover text-primary me-1">the interviewee</a>and their
                    <br/>work following this is a transcript of the interview.
                </div>
                <!--end::Text-->
            </div>
            <!--end::Content-->
        </div>
        <!--begin::Aside-->
        @yield('content')
    </div>
    <!--end::Authentication - Sign-in-->
</div>
<!--end::Root-->
<!--end::Main-->
<!--begin::Javascript-->
<!--begin::Global Javascript Bundle(used by all pages)-->
<script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
<script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
<!--end::Global Javascript Bundle-->
<!--begin::Custom Javascript(used by this page)-->
<script src="{{ asset('assets/js/custom/authentication/sign-in/general.js') }}"></script>
<!--end::Custom Javascript-->
<!--end::Javascript-->
@stack('custom_scripts')
</body>
<!--end::Body-->
</html>
