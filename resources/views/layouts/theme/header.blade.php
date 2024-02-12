<div>
    <style type="text/css">
        header {
            backdrop-filter: blur(10px);
        }
    </style>
    <div class="header-container fixed-top" style="backdrop-filter: blur(5px);">
        <header class="header navbar navbar-expand-sm" style="backdrop-filter: blur(10px);">
            <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list">
                    <line x1="8" y1="6" x2="21" y2="6"></line>
                    <line x1="8" y1="12" x2="21" y2="12"></line>
                    <line x1="8" y1="18" x2="21" y2="18"></line>
                    <line x1="3" y1="6" x2="3" y2="6"></line>
                    <line x1="3" y1="12" x2="3" y2="12"></line>
                    <line x1="3" y1="18" x2="3" y2="18"></line>
                </svg>
            </a>
      
            <ul class="navbar-item flex-row">
                <li class="nav-item theme-logo">
                    <a href="{{route('dash')}}">
                        <img src="https://firebasestorage.googleapis.com/v0/b/latin-food-8635c.appspot.com/o/splash%2FlogoAnimadoNaranjaLoop.gif?alt=media&token=0f2cb2ee-718b-492c-8448-359705b01923" width="400" height="341" class="navbar-logo" alt="logo" loop="true">
                        <b style="font-size: 19px; color:#3B3F5C"> K&D Latin Food Inc</b>
                    </a>
                </li>
            </ul>
   <ul class="navbar-item flex-row">
     <li class="nav-item">
            <script>
                setInterval(function() {
                    var ahora = new Date();
                    var hora = ahora.getHours();
                    var minutos = ahora.getMinutes();
                    var segundos = ahora.getSeconds();
                    var horaFormateada = hora + ':' + minutos;
                    document.getElementById('hora-actual').textContent = horaFormateada;
                }, 1000);
            </script>
        </li>
           </ul> 
            <div>
                <a href="{{ url('messages') }}">
                    Messages
                </a>
            </div>

            <livewire:search-controller>
            
            <span>&ensp; &ensp;</span>
               <ul class="navbar-item flex-row">
            <h5>
                Welcome! @guest Alberto| @else {{Auth()->user()->name}} @endguest 
                <span>&ensp; &ensp;</span> 
                Time: <h5 id="hora-actual"></h5>
            </h5>
</ul>
       <ul class="navbar-item flex-row">
                <div class="btn-group ml-2">
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-bell"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#"></a>
                    </div>
                    <span>&ensp; </span>
                </div>
    </ul>
            <ul class="navbar-item flex-row navbar-dropdown">
                <style type="text/css">
                    .customer-profile img {
                        border-radius: 50%;
                        height: 100px;
                        width: 100px;
                    }
                </style>
                <li class="nav-item dropdown user-profile-dropdown  order-lg-0 order-1" style="backdrop-filter: blur(10px);">
                    <a title="User" href="javascript:void(0);" class="nav-link dropdown-toggle user" id="userProfileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                       <img class="customer-profile" src="{{ asset('storage/users/' . Auth()->user()->image) }}" alt="Imagen del usuario" style=" border-radius: 50%;" width="120" height="120">
                    </a>
                    <div class="dropdown-menu position-absolute animated fadeInUp" aria-labelledby="userProfileDropdown" style="backdrop-filter: blur(10px);">
                        <div class="user-profile-section">
                            <div class="media mx-auto">
                                <div class="media-body">
                                    <h5>@guest Alberto @else {{Auth()->user()->name}} @endguest</h5>
                                    <p>{{Auth()->user()->profile}}</p>
                                    <p>{{Auth()->user()->email}}</p>
                                    <p>{{Auth()->user()->status}}</p>
                                    <img src="{{ asset('storage/users/' . Auth()->user()->image) }}" alt="Imagen del usuario" width="100" height="100">
                                </div>
                            </div>
                        </div>
                        <div class="dropdown-item">
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                    <polyline points="16 17 21 12 16 7"></polyline>
                                    <line x1="21" y1="12" x2="9" y2="12"></line>
                                </svg> <span>Exit</span>
                            </a>
                            <form action="{{ route('logout') }}" method="POST" id="logout-form">
                                @csrf
                            </form>
                        </div>
                    </div>
                </li>
            </ul>
        </header>
    </div>
</div>
