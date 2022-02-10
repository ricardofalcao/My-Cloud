<?php

if (!isset($file)) {
    return;
}

$folder = $file['type'] === 'FOLDER';
$favorite = $file['state'] === 'FAVORITE';
$deleted = $file['state'] === 'DELETED';

$path_parts = pathinfo($file['name']);
$filename = $path_parts['filename'];
$extension = $folder ? '' : $path_parts['extension'];

$icon = 'file';

if ($folder) {
    $icon = 'folder';
} else {
    $iconMap = [
        '(doc|docm|docx|odt)' => 'file-word',
        '(potx|pptx)' => 'file-powerpoint',
        '(ods|xls|xlsx|xml)' => 'file-excel',
        '(csv)' => 'file-csv',
        '(pdf)' => 'file-pdf',
        '(webm|mkv|flv|wmv|avi|mp4|m4p|m4v|mpg|mpeg|mpv)' => 'file-video',
        '(jpeg|jpg|png|gif|tiff|raw)' => 'file-image',
        '(7z|rar|zip|tar|tar.gz)' => 'file-archive',
    ];

    foreach ($iconMap as $regex => $iconT) {
        if (preg_match($regex, $extension)) {
            $icon = $iconT;
            break;
        }
    }
}

$size = $file['size'];

$_sz = 'BKMGTP';
$_factor = floor((strlen($size) - 1) / 3);
$humanSize = sprintf("%.1f", $size / pow(1024, $_factor)) . @$_sz[$_factor];

?>

<tr class="datatable-item" id="file-<?php echo $file['id'] ?>">
    <td class="file-checkbox"><input type="checkbox"></td>
    <td class="file-icon <? echo $folder ? 'colored' : '' ?>">
        <i class="fas fa-<? echo $icon ?>"></i>
    </td>
    <td class="file-name">
        <? if ($folder) { ?>
            <a class="file-link" href="/drive/files/<? echo $file['id'] ?>"><? echo $filename ?></a>
        <? } else { ?>
            <? echo $filename ?><span class="file-extension">.<? echo $extension; ?></span>
        <? } ?>
    </td>
    <td class="file-options">
        <i class="fas fa-ellipsis-h"></i>

        <ul class="dropdown">
            <li class="dropdown-link">
                <button onclick="favoriteFile(<? echo $file['id'] ?>, <? echo !$favorite ?>)">
                    <i style="color: yellow;" class="fas fa-star"></i>
                    <? if ($favorite) { ?>
                        Remover dos favoritos
                    <? } else { ?>
                        Adicionar aos favoritos
                    <? } ?>
                </button>
            </li>
            <li class="dropdown-link">
                <a href="#">
                    <i class="fas fa-pen"></i>
                    Renomear
                </a>
            </li>
            <?
            if (!$folder) {
                ?>
                <li class="dropdown-link">
                    <a href="/drive/download/<? echo $file['id'] ?>">
                        <i class="fas fa-download"></i>
                        Transferir
                    </a>
                </li>
            <? } ?>
            <li class="dropdown-link">
                <button onclick="deleteFile(<? echo $file['id'] ?>, <? echo $deleted ?>)">
                    <i class="fas fa-trash"></i>
                    Eliminar
                </button>
            </li>
            <?
            if ($deleted) {
                ?>
                <li class="dropdown-link">
                    <button onclick="restoreFile(<? echo $file['id'] ?>)">
                        <i class="fas fa-recycle"></i>
                        Restaurar
                    </button>
                </li>
            <? } ?>
        </ul>
    </td>
    <td class="file-size"><? echo $humanSize ?></td>
</tr>
