<?php

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
