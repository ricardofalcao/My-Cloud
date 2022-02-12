<?php

class CloudFile {
    public $id;
    public $name;
    public $extension;
    public $size;
    public $icon;
    public $folder;
    public $favorite;

    public function __construct(int $id, string $name, string $extension, int $size, bool $folder, bool $favorite) {
        $this->id = $id;
        $this->name = $name;
        $this->extension = $extension;
        $this->size = static::_humanFilesize($size);
        $this->icon = static::_extension2Icon($extension, $folder);
        $this->folder = $folder;
        $this->favorite = $favorite;
    }
}

?>