<?php
class SidebarLink {
    public $id;
    public $name;
    public $icon;
    public $quantity;
    public $target;

    public function __construct(string $id, string $name, string $icon, int $quantity, string $target) {
        $this->id = $id;
        $this->name = $name;
        $this->icon = $icon;
        $this->quantity = $quantity;
        $this->target = $target;
    }
}

$sidebar_links = array(
    new SidebarLink('files', 'Ficheiros', 'folder', 1, '/drive/files'),
    new SidebarLink('shared', 'Partilhados', 'share-alt', 6, '/drive/shared'),
    new SidebarLink('favorites', 'Favoritos', 'star', 24, '/drive/favorites'),
);
?>

<nav class="sidebar">
    <ul class="sidebar-top">
        <?php 
            foreach($sidebar_links as $link) {
        ?>

        <li class="sidebar-link <? echo $link->id === $sidebar_current_id ? "active" : "" ?>">
            <a href="<? echo $link->target ?>">
                <span class="sidebar-label">
                    <i class="fas fa-<? echo $link->icon ?>"></i>
                    <span class="sidebar-label-text"><? echo $link->name ?></span>
                </span>
                <span class="sidebar-number"><? echo $link->quantity ?></span>
            </a>
        </li>

        <?php
            }
        ?>
    </ul>

    <ul class="sidebar-bottom">
        <li class="sidebar-link <? echo "trash" === $sidebar_current_id ? "active" : "" ?>">
            <a href="/drive/trash">
                <span class="sidebar-label">
                    <i class="fas fa-trash"></i>
                    <span class="sidebar-label-text">Reciclagem</span>
                </span>
                <span class="sidebar-number">99</span>
            </a>
        </li>

        <li class="sidebar-text">
            2.4/15.0GB utilizados
        </li>
    </ul>
</nav>