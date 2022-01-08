<?php
class NavbarLink {
    public $id;
    public $name;
    public $target;

    public function __construct(string $id, string $name, string $target) {
        $this->id = $id;
        $this->name = $name;
        $this->target = $target;
    }
}

$navbar_links = array(
    new NavbarLink('index', 'Início', '/'),
    new NavbarLink('login', 'Login', '/login'),
    new NavbarLink('settings', 'Settings', '/settings')
);
?>

<nav class="navbar">
    <div class="navbar-title">
        <i class="fas fa-cloud"></i>
        <span class="navbar-title-text">My Cloud</span>
    </div>

    <ul class="navbar-links">
        <?php 
            foreach($navbar_links as $link) {
        ?>

        

        <li class="navbar-link <? echo $link->id === $navbar_current_id ? "active" : "" ?>">
            <a href="<? echo $link->target ?>"><? echo $link->name ?></a>
        </li>

        <?php
            }
        ?>

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
                    <a href="#">
                        <i class="fas fa-sign-out-alt"></i>
                        Sair
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</nav>