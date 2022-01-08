<?php

class CloudFile {
    public $name;
    public $extension;
    public $size;
    public $icon;
    public $folder;

    public function __construct(string $name, string $extension, int $size, bool $folder) {
        $this->name = $name;
        $this->extension = $extension;
        $this->size = static::_humanFilesize($size);
        $this->icon = static::_extension2Icon($extension, $folder);
        $this->folder = $folder;
    }
    
    private static function _humanFilesize($bytes, $decimals = 1) {
        $sz = 'BKMGTP';
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
    }

    private static function _extension2Icon($extension, $folder) {
        static $iconMap = [
            '(doc|docm|docx|odt)' => 'file-word',
            '(potx|pptx)' => 'file-powerpoint',
            '(csv|ods|xls|xlsx|xml)' => 'file-excel',
            '(pdf)' => 'file-pdf',
            '(webm|mkv|flv|wmv|avi|mp4|m4p|m4v|mpg|mpeg|mpv)' => 'file-video',
            '(jpeg|jpg|png|gif|tiff|raw)' => 'file-image',
        ];

        if ($folder) {
            return 'folder';
        }

        foreach($iconMap as $regex => $icon) {
            if (preg_match($regex, $extension)) {
                return $icon;
            }
        }

        return 'file';
    }
}

?>