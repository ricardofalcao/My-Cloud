<nav class="navbar">
    <div class="navbar-title">
        <i class="fas fa-cloud"></i>
        <span class="navbar-title-text">My Cloud</span>
    </div>

    <ul class="navbar-links">
        <li class="navbar-avatar">
            <img src="/assets/images/avatar_ricardofalcao.jpg"/>

            <div class="arrow"></div>
            <ul class="dropdown">
                <li class="dropdown-title">Ricardo Falcão</li>
                <li class="dropdown-link">
                    <a href="#">
                        <i class="fas fa-cogs"></i>
                        Definições
                    </a>
                </li>
                <li class="dropdown-link">
                    <form action="/auth/logout" method="POST">
                        <button type="submit">
                            <i class="fas fa-sign-out-alt"></i>
                            Sair
                        </button>
                    </form>
                </li>
            </ul>
        </li>
    </ul>
</nav>