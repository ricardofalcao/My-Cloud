<?php

use Core\Request;
use Core\Utils;

require_once 'sidebar_common.php';

$sidebar_links = array(
    new SidebarLink('files', 'Ficheiros', 'folder', '/drive/files'),
    new SidebarLink('shared', 'Partilhados', 'share-alt',  '/drive/shared'),
    new SidebarLink('favorites', 'Favoritos', 'star',  '/drive/favorites'),
);

$user = Request::get('user');

?>

<aside class="column is-3 is-2-desktop menu is-flex is-flex-direction-column is-align-items-stretch right-border" id="sidebar">
    <ul class="menu-list">

        <?php  foreach($sidebar_links as $link) { ?>

        <li>
            <a href="<?php echo $link->target ?>" class="item is-flex is-align-items-center py-4 px-4 <?php echo $sidebar_current_id === $link->id ? 'is-active' : '' ?>">
                <span class="icon mr-2">
                    <i class="fas fa-<?php echo $link->icon ?> is-size-6"></i>
                </span>
                <span class="name is-size-6"><?php echo $link->name ?></span>
                <span class="tag is-white is-rounded ml-auto all-border" data-value="<?php echo $link->id ?>"><?php echo array_key_exists($link->id, $count) ? $count[$link->id] : 0 ?></span>
            </a>
        </li>

        <?php } ?>
    </ul>

    <ul class="menu-list mt-auto mb-4">
        <li>
            <a href="/drive/trash" class=" is-flex is-align-items-center is-justify-content-space-between py-4 px-4 <?php echo $sidebar_current_id === 'trash' ? 'is-active' : '' ?>">
                <span class="icon mr-2 is-size-6">
                    <i class="fas fa-trash"></i>
                </span>
                <span class="name is-size-6">Reciclagem</span>
                <span class="tag is-white is-rounded ml-auto all-border" data-value="trash"><?php echo array_key_exists('trash', $count) ? $count['trash'] : 0 ?></span>
            </a>
        </li>

        <li>
            <?php
            if (empty($user['quota'])) {
            ?>
                <p class="has-text-centered has-text-grey-light is-size-6"><?php echo Utils::humanizeBytes($count['disk_usage']) ?> utilizados</p>
            <?php
            } else {
            ?>
                <p class="has-text-centered has-text-grey-light is-size-6"><?php echo Utils::humanizeBytes($count['disk_usage']) ?> / <?php echo  Utils::humanizeBytes($user['quota']) ?> utilizados</p>
            <?php
            }
            ?>
        </li>
    </ul>
</aside>