<?php
use Core\Request;

$user = Request::get('user');

if(!isset($showSearch)) {
    $showSearch = true;
}

?>

<nav class="navbar is-primary" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
        <a class="navbar-item" href="/drive/files">
                    <span class="icon mr-2">
                        <i class="fa fa-cloud"></i>
                    </span>

            <span>My Cloud</span>
        </a>

        <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
        </a>
    </div>
    <div id="navbar" class="navbar-menu">
        <div class="navbar-end">
            <?php if ($showSearch) {
            ?>
                <div class="navbar-item">
                    <form action="/drive/files">

                        <input type="submit" style="display: none;"/>
                        <div class="field">
                            <p class="control has-icons-left">
                                <input class="input is-rounded is-small" type="text" name="search" placeholder="Search">
                                <span class="icon is-small is-left">
                                  <i class="fas fa-search"></i>
                                </span>
                            </p>
                        </div>
                    </form>
                </div>
            <?php
            } ?>

            <div class="navbar-item has-dropdown is-hoverable is-right">
                <a class="navbar-link has-text-white is-arrowless">
                    <?php echo $user['name']; ?>
                </a>

                <div class="navbar-dropdown is-right">
                    <span class="navbar-item has-text-grey">
                        <?php echo $user['name']; ?>
                    </span>

                    <a class="navbar-item" href="../../dashboard/user/profile">
                        <span class="icon mr-2">
                            <i class="fas fa-cogs"></i>
                        </span>
                        <span>Painel de Controlo</span>
                    </a>

                    <a class="navbar-item" href="../../auth/logout">
                        <span class="icon mr-2">
                            <i class="fas fa-sign-out-alt"></i>
                        </span>
                        <span>Sair</span>
                    </a>
                </div>
            </div>
            </div>
        </div>
    </div>
</nav>
