<script src="{{ asset('assets/js/loader.js') }}"></script>
<link href="{{ asset('assets/css/loader.css') }}" rel="stylesheet" type="text/css" />

<link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">

<link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/css/plugins.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/css/structure.css') }}" rel="stylesheet" type="text/css" class="structure" />

<link href="{{ asset('plugins/font-icons/fontawesome/css/fontawesome.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('css/fontawesome.css') }}" rel="stylesheet" type="text/css" />

<link href="{{ asset('assets/css/elements/avatar.css') }}" rel="stylesheet" type="text/css" />

<link href="{{ asset('plugins/sweetalerts/sweetalert.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('plugins/notification/snackbar/snackbar.min.css') }}" rel="stylesheet" type="text/css" />


<link href="{{ asset('css/custom.css') }}" rel="stylesheet" type="text/css" />

<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/widgets/modules-widgets.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/forms/theme-checkbox-radio.css') }}">

<link href="{{ asset('assets/css/apps/scrumboard.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/css/apps/notes.css') }}" rel="stylesheet" type="text/css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/push.js/1.0.8/push.min.js"
    integrity="sha512-eiqtDDb4GUVCSqOSOTz/s/eiU4B31GrdSb17aPAA4Lv/Cjc8o+hnDvuNkgXhSI5yHuDvYkuojMaQmrB5JB31XQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/push.js/1.0.9/push.min.js"></script>
<style>
aside {
    display: none !important;
}

.page-item.active .page-link {
    z-index: 3;
    color: #fff;
    background-color: #FF5100;
    border-color: #FF5100;
}

@media (max-width: 480px) {
    .mtmobile {
        margin-bottom: 20px !important;
    }

    .mbmobile {
        margin-bottom: 10px !important;
    }

    .hideonsm {
        display: none !important;
    }

    .inblock {
        display: block;
    }
}

/*sidebar background*/
.sidebar-theme #compactSidebar {
    background: #FF5100 !important;
}

/*sidebar collapse background */
.header-container .sidebarCollapse {
    backdrop-filter: blur(10px);
    color: #FF5100 !important;
}

.navbar .navbar-item .nav-item form.form-inline input.search-form-control {
    font-size: 15px;
    backdrop-filter: blur(10px);
    background-color: #FF5100 !important;
    padding-right: 40px;
    padding-top: 12px;
    border: none;
    color: #fff;
    box-shadow: none;
    border-radius: 30px;
}
</style>


<link href="{{ asset('plugins/flatpickr/flatpickr.dark.css') }}" rel="stylesheet" type="text/css" />

@livewireStyles