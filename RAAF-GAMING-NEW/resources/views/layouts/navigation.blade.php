<link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
<nav class="navbar-bg rounded-b-[40px] shadow-lg">
    <div class="flex items-center justify-between px-4 py-2">
        <!-- Logo and Toggler -->
        <div class="flex items-center gap-3">
            <a href="#" class="inline-block focus-visible" aria-label="RAAF-GAMING Home">
                <img src="{{ asset('immagini/logo.png') }}" alt="RAAF-GAMING" class="w-[75px] h-auto">
            </a>
            <!-- Burger menu senza border e con 3 linee -->
            <button 
                type="button"
                data-toggle="collapse" 
                data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" 
                aria-expanded="false" 
                aria-label="Toggle navigation menu"
                class="navbar-toggler h-[30px] px-3 text-white rounded-[30px] focus-visible cursor-pointer">
                <span class="hamburger-icon"></span>
            </button>
        </div>
        
        <!-- Search Form -->
        <div class="flex-1 flex justify-center mx-4">
            <form action="#" method="GET" class="search-form w-full max-w-md" role="search">
                <div class="relative">
                    <label for="search-input" class="sr-only">Cerca giochi, console, abbonamenti</label>
                    <input 
                        id="search-input"
                        type="search" 
                        placeholder="Cerca giochi, console, abbonamenti..." 
                        name="ricerca"
                        class="search-input w-full px-4 py-2 pr-10 rounded-[15px] border-2 border-black bg-white focus:outline-none focus:ring-2 focus:ring-gray-400"
                        aria-label="Campo di ricerca">
                    <div class="search-icon absolute right-2 top-1/2 -translate-y-1/2 w-[23px] h-[23px]" 
                         style="background-image: url('{{ asset('immagini/lente.png') }}');"
                         aria-hidden="true">
                    </div>
                </div>
            </form>
        </div>
        
        <!-- User Dropdown and Cart -->
        <div class="flex items-center gap-4 mr-5">
            <!-- User Dropdown -->
            <div class="relative dropdown">
                <!-- Icona astronauta allineata con il carrello -->
                <button 
                    type="button"
                    id="dropdownMenuButton"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                    aria-label="User menu"
                    class="user-avatar mt-1.5 focus-visible">
                    <i class='fas fa-user-astronaut text-[27px] text-white hover:text-white/60' aria-hidden="true"></i>
                </button>
                <div class="dropdown-menu absolute right-0 mt-2 navbar-bg rounded-tl-[5px] rounded-br-[15px] shadow-lg min-w-[150px] z-50" 
                     aria-labelledby="dropdownMenuButton"
                     role="menu">
                    @if(isset($impostazione) && isset($impostazione2))
                        @foreach($impostazione as $index => $label)
                            <a href="{{ route($impostazione2[$index]) }}" 
                               class="dropdown-item block px-4 py-2 text-white hover:bg-gray-500 focus-visible"
                               role="menuitem">
                                {{ $label }}
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>
            
            <!-- Shopping Cart -->
            <a href="{{ route('logout') }}" class="cart-icon mt-0.95 mr-2.5 focus-visible" aria-label="Carrello della spesa">
                @if(empty($carrello))
                    <i id="sostituisciCarrello" class='fas fa-shopping-cart text-[27px] text-white hover:text-white/60' aria-hidden="true"></i>
                    <span class="sr-only">Carrello vuoto</span>
                @else
                    <i class='fa fa-cart-arrow-down text-[27px] text-white hover:text-white/60' aria-hidden="true"></i>
                    <span class="sr-only">Carrello con articoli</span>
                @endif
            </a>
        </div>
    </div>
    
    <!-- Collapsible Menu -->
    <!-- Menu riempie la navbar in orizzontale, non verticale -->
    <div class="navbar-collapse" id="navbarSupportedContent">
        <ul class="flex flex-row flex-wrap pt-2 px-4 pb-2 gap-x-6 gap-y-2" role="menubar">
            <li role="none">
                <a href="#" class="text-decoration-none nav-link-mobile inline-flex items-center px-3 py-2 text-white text-base hover:bg-white/10 rounded-lg focus-visible" role="menuitem">
                    <i class='fas fa-book text-base' aria-hidden="true"></i>&nbsp;&nbsp;Catalogo
                </a>
            </li>
            <li role="none">
                <a href="#" class="text-decoration-none nav-link-mobile inline-flex items-center px-3 py-2 text-white text-base hover:bg-white/10 rounded-lg focus-visible" role="menuitem">
                    <i class="fa fa-star text-base" aria-hidden="true"></i>&nbsp;&nbsp;Azione
                </a>
            </li>
            <li role="none">
                <a href="#" class="text-decoration-none nav-link-mobile inline-flex items-center px-3 py-2 text-white text-base hover:bg-white/10 rounded-lg focus-visible" role="menuitem">
                    <i class='fab fa-phoenix-squadron text-base' aria-hidden="true"></i>&nbsp;&nbsp;Avventura
                </a>
            </li>
            <li role="none">
                <a href="#" class="text-decoration-none nav-link-mobile inline-flex items-center px-3 py-2 text-white text-base hover:bg-white/10 rounded-lg focus-visible" role="menuitem">
                    <i class='fas fa-crosshairs text-base' aria-hidden="true"></i>&nbsp;&nbsp;Battle Royale
                </a>
            </li>
            <li role="none">
                <a href="#" class="text-decoration-none nav-link-mobile inline-flex items-center px-3 py-2 text-white text-base hover:bg-white/10 rounded-lg focus-visible" role="menuitem">
                    <i class="fa fa-futbol text-base" aria-hidden="true"></i>&nbsp;&nbsp;Sport
                </a>
            </li>
            <li role="none">
                <a href="#" class="text-decoration-none nav-link-mobile inline-flex items-center px-3 py-2 text-white text-base hover:bg-white/10 rounded-lg focus-visible" role="menuitem">
                    <i class='fas fa-ghost text-base' aria-hidden="true"></i>&nbsp;&nbsp;Horror
                </a>
            </li>
            <li role="none">
                <a href="#" class="text-decoration-none nav-link-mobile inline-flex items-center px-3 py-2 text-white text-base hover:bg-white/10 rounded-lg focus-visible" role="menuitem">
                    <i class='fab fa-playstation text-base' aria-hidden="true"></i>&nbsp;&nbsp;Console
                </a>
            </li>
            <li role="none">
                <a href="#" class="text-decoration-none nav-link-mobile inline-flex items-center px-3 py-2 text-white text-base hover:bg-white/10 rounded-lg focus-visible" role="menuitem">
                    <i class="fa fa-gamepad text-base" aria-hidden="true"></i>&nbsp;&nbsp;Videogiochi
                </a>
            </li>
            <li role="none">
                <a href="#" class="text-decoration-none nav-link-mobile inline-flex items-center px-3 py-2 text-white text-base hover:bg-white/10 rounded-lg focus-visible" role="menuitem">
                    <i class="fa fa-calendar-check text-base" aria-hidden="true"></i>&nbsp;&nbsp;Abbonamenti
                </a>
            </li>
            <li role="none">
                <a href="#" class="text-decoration-none nav-link-mobile inline-flex items-center px-3 py-2 text-white text-base hover:bg-white/10 rounded-lg focus-visible" role="menuitem">
                    <i class="fa fa-plus-circle text-base" aria-hidden="true"></i>&nbsp;&nbsp;DLC
                </a>
            </li>
        </ul>
    </div>
</nav>

<!-- Side Panel (if needed) -->
<div id="mySidepanel" class="sidepanel" role="dialog" aria-label="Side navigation panel">
    <a href="javascript:void(0)" 
       class="closebtn" 
       onclick="closeNav()"
       aria-label="Close navigation panel">&times;</a>
    <!-- Add your side panel links here -->
</div>

<script>
    // Dropdown toggle functionality
    document.addEventListener('DOMContentLoaded', function() {
        const dropdownButton = document.getElementById('dropdownMenuButton');
        const dropdownMenu = dropdownButton?.nextElementSibling;
        
        if (dropdownButton && dropdownMenu) {
            dropdownButton.addEventListener('click', function(e) {
                e.stopPropagation();
                const isExpanded = dropdownButton.getAttribute('aria-expanded') === 'true';
                dropdownButton.setAttribute('aria-expanded', !isExpanded);
                dropdownMenu.classList.toggle('active');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                    dropdownMenu.classList.remove('active');
                    dropdownButton.setAttribute('aria-expanded', 'false');
                }
            });
            
            // Close on escape key
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && dropdownMenu.classList.contains('active')) {
                    dropdownMenu.classList.remove('active');
                    dropdownButton.setAttribute('aria-expanded', 'false');
                    dropdownButton.focus();
                }
            });
        }
        
        // Navbar collapse toggle
        const navbarToggler = document.querySelector('[data-toggle="collapse"]');
        const navbarContent = document.getElementById('navbarSupportedContent');
        
        if (navbarToggler && navbarContent) {
            navbarToggler.addEventListener('click', function() {
                const isExpanded = navbarToggler.getAttribute('aria-expanded') === 'true';
                navbarToggler.setAttribute('aria-expanded', !isExpanded);
                navbarContent.classList.toggle('active');
            });
        }
    });
    
    // Side panel functions
    function openNav() {
        const sidepanel = document.getElementById("mySidepanel");
        if (sidepanel) {
            sidepanel.style.width = "250px";
            sidepanel.setAttribute('aria-hidden', 'false');
        }
    }
    
    function closeNav() {
        const sidepanel = document.getElementById("mySidepanel");
        if (sidepanel) {
            sidepanel.style.width = "0";
            sidepanel.setAttribute('aria-hidden', 'true');
        }
    }
</script>


