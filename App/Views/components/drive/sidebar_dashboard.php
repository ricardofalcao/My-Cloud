<?php

use Core\Request;
use Core\Utils;

require_once 'sidebar_common.php';

$sidebar_user_links = array(
    new SidebarLink('user_profile', 'Perfil', 'user', '/dashboard/user/profile'),
    new SidebarLink('user_stats', 'Estatísticas', 'chart-pie', '/dashboard/user/stats'),
);

$sidebar_admin_links = array(
    new SidebarLink('admin_users', 'Utilizadores', 'users', '/dashboard/admin/users'),
    new SidebarLink('admin_stats', 'Estatísticas', 'chart-pie', '/dashboard/admin/stats'),
);

$user = Request::get('user');

?>

<aside class="column is-3 is-2-desktop menu is-flex is-flex-direction-column is-align-items-stretch right-border" id="sidebar">
    <ul class="menu-list">

        <p class="menu-label ml-4 mt-4" style="font-size: 15px;">
            Utilizador
        </p>
        <?php  foreach($sidebar_user_links as $link) { ?>

            <li>
                <a href="<?php echo $link->target ?>" class="item is-flex is-align-items-center py-4 px-4 <?php echo $sidebar_current_id === $link->id ? 'is-active' : '' ?>">
                <span class="icon mr-2">
                    <i class="fas fa-<?php echo $link->icon ?> is-size-6"></i>
                </span>
                    <span class="name is-size-6"><?php echo $link->name ?></span>
                </a>
            </li>

        <?php } ?>

        <?php if ($user['role'] === 'SUPERUSER') {  ?>
        <p class="menu-label ml-4 mt-4" style="font-size: 15px;">
            Administrador
        </p>
        <?php foreach($sidebar_admin_links as $link) { ?>

            <li>
                <a href="<?php echo $link->target ?>" class="item is-flex is-align-items-center py-4 px-4 <?php echo $sidebar_current_id === $link->id ? 'is-active' : '' ?>">
                <span class="icon mr-2">
                    <i class="fas fa-<?php echo $link->icon ?> is-size-6"></i>
                </span>
                    <span class="name is-size-6"><?php echo $link->name ?></span>
                </a>
            </li>

        <?php } } ?>
    </ul>
</aside>