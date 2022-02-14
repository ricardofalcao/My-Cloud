<?
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
            <? if ($showSearch) {
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
            <?
            } ?>

            <div class="navbar-item has-dropdown is-hoverable">
                <div class="navbar-link">
                    <? echo $user['name']; ?>
                </div>

                <div id="moreDropdown" class="navbar-dropdown is-right">
                    <a class="navbar-item " href="http://bulma.io/extensions/">
                        <div class="level is-mobile">
                            <div class="level-left">
                                <div class="level-item">
                                    <p>
                                        <strong>Extensions</strong>
                                        <br>
                                        <small>Side projects to enhance Bulma</small>
                                    </p>
                                </div>
                            </div>

                            <div class="level-right">
                                <div class="level-item">
                                              <span class="icon has-text-info">
                                                <i class="fa fa-plug"></i>
                                              </span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>
