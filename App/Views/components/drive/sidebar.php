<?php

use Core\Request;
use Core\Utils;

class SidebarLink {
    public $id;
    public $name;
    public $icon;
    public $target;

    public function __construct(string $id, string $name, string $icon, string $target) {
        $this->id = $id;
        $this->name = $name;
        $this->icon = $icon;
        $this->target = $target;
    }
}

$sidebar_links = array(
    new SidebarLink('files', 'Ficheiros', 'folder', '/drive/files'),
    new SidebarLink('shared', 'Partilhados', 'share-alt',  '/drive/shared'),
    new SidebarLink('favorites', 'Favoritos', 'star',  '/drive/favorites'),
);

$user = Request::get('user');
$total_space = 2.4e6;

?>

<aside class="column is-3 is-2-desktop menu is-flex is-flex-direction-column is-align-items-stretch right-border">
    <ul class="menu-list">

        <?  foreach($sidebar_links as $link) { ?>

        <li>
            <a href="<? echo $link->target ?>" class="item is-flex is-align-items-center py-4 px-4 <? echo $sidebar_current_id === $link->id ? 'is-active' : '' ?>">
                <span class="icon mr-2">
                    <i class="fas fa-<? echo $link->icon ?> is-size-6"></i>
                </span>
                <span class="name is-size-6"><? echo $link->name ?></span>
                <span class="tag is-white is-rounded ml-auto all-border"><? echo array_key_exists($link->id, $count) ? $count[$link->id] : 0 ?></span>
            </a>
        </li>

        <? } ?>
    </ul>

    <ul class="menu-list mt-auto mb-4">
        <li>
            <a href="/drive/trash" class=" is-flex is-align-items-center is-justify-content-space-between py-4 px-4 <? echo $sidebar_current_id === 'trash' ? 'is-active' : '' ?>">
                <span class="icon mr-2 is-size-6">
                    <i class="fas fa-trash"></i>
                </span>
                <span class="name is-size-6">Reciclagem</span>
                <span class="tag is-white is-rounded ml-auto all-border"><? echo array_key_exists('trash', $count) ? $count['trash'] : 0 ?></span>
            </a>
        </li>

        <li>
            <?
            if (empty($user['quota'])) {
            ?>
                <p class="has-text-centered has-text-grey-light is-size-6"><? echo Utils::humanizeBytes($total_space) ?> utilizados</p>
            <?
            } else {
            ?>
                <p class="has-text-centered has-text-grey-light is-size-6"><? echo Utils::humanizeBytes($total_space) ?> / <? echo  Utils::humanizeBytes($user['quota']) ?> utilizados</p>
            <?
            }
            ?>
        </li>
    </ul>
</aside>