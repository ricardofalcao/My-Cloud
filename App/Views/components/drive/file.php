<?php

if (!isset($file) || !isset($index)) {
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
    <td style="vertical-align: middle; font-size: 1.4rem;">
        <input type="checkbox" class="row-checkbox has-text-primary" onclick="check(this, event.shiftKey)">
    </td>

    <td style="width: 99%">
        <span class="icon-text is-flex is-align-items-center">
            <span class="icon mr-3">
                <i class="fas fa-lg fa-<? echo $icon ?> <? echo $folder ? 'has-text-primary' : ''?> "></i>
            </span>

            <span class="py-3">

                <? if ($folder) { ?>
                    <a href="/drive/files/<? echo $file['id'] ?>" class="has-text-black"><? echo $filename ?></a>
                <? } else { ?>
                    <? echo $filename ?><span class="has-text-grey-light">.<? echo $extension; ?></span>
                <? } ?>

            </span>

            <div class="ml-auto dropdown is-hoverable is-right">
                <div class="dropdown-trigger">
                  <button class="button is-small is-black is-inverted" aria-haspopup="true"
                          aria-controls="dropdown-menu">
                    <span class="icon is-small">
                      <i class="fas fa-ellipsis-h" aria-hidden="true"></i>
                    </span>
                  </button>
                </div>
                <div class="dropdown-menu" id="dropdown-menu" role="menu">
                    <div class="dropdown-content">
                        <button class="dropdown-item" onclick="favoriteFile(<? echo $file['id'] ?>, <? echo !$favorite ?>)">
                            <span class="icon-text">
                                <span class="icon">
                                    <i style="color: yellow;" class="fas fa-star"></i>
                                </span>

                                <? if ($favorite) { ?>
                                    Remover dos favoritos
                                <? } else { ?>
                                    Adicionar aos favoritos
                                <? } ?>
                            </span>
                        </button>

                        <a href="#" class="dropdown-item">
                            <span class="icon-text">
                                <span class="icon">
                                    <i class="fas fa-pen"></i>
                                </span>
                                <span>Renomear</span>
                            </span>
                        </a>

                        <a href="/drive/download?files[]=<? echo $file['id'] ?>" class="dropdown-item">
                            <span class="icon-text">
                                <span class="icon">
                                    <i class="fas fa-download"></i>
                                </span>
                                <span>Transferir</span>
                            </span>
                        </a>

                        <button class="dropdown-item" onclick="deleteFile(<? echo $file['id'] ?>, <? echo $deleted ?>)">
                            <span class="icon-text">
                                <span class="icon">
                                    <i class="fas fa-trash"></i>
                                </span>
                                <span>Eliminar</span>
                            </span>
                        </button>

                        <? if ($deleted) {
                        ?>
                            <button class="dropdown-item" onclick="restoreFile(<? echo $file['id'] ?>)">
                            <span class="icon-text">
                                <span class="icon">
                                    <i class="fas fa-recycle"></i>
                                </span>
                                <span>Restaurar</span>
                            </span>
                        </button>
                        <?
                        } ?>
                    </div>
                </div>
            </div>
        </span>
    </td>
    <td style="vertical-align: middle">
        <? if ($size > 0) {
            echo $humanSize;
        } ?>
    </td>
</tr>
