<?php

use Core\Utils;

if (!isset($file)) {
    return;
}

$folder = $file['type'] === 'FOLDER';
$favorite = $file['state'] === 'FAVORITE';
$deleted = $file['state'] === 'DELETED';

$path_parts = pathinfo($file['name']);
$filename = $path_parts['filename'];
$extension = $folder ? '' : $path_parts['extension'];

$icon = Utils::iconFromExtension($extension);

$size = $file['size'];
$humanSize = Utils::humanizeBytes($size);

?>

<td style="vertical-align: middle; font-size: 1.4rem; white-space: nowrap;">
    <input type="checkbox" data-value="<? echo $file['id'] ?>" class="row-checkbox has-text-primary" onclick="check(this, event.shiftKey)">
</td>

<td>
        <span class="icon-text is-flex is-align-items-center">
            <span class="icon mr-3 is-relative">
                <i class="fas fa-lg fa-<? echo $icon ?> <? echo $folder ? 'has-text-primary' : '' ?> "></i>

                <i style="color: #f6d16b; position: absolute; top: 0; right: -4px;"
                   class="favorite fas fa-star is-size-7 <? echo $favorite ? '' : 'is-hidden' ?>"></i>
            </span>

            <span class="py-3 row-name">

                <? if ($folder) { ?>
                    <a href="/drive/files/<? echo $file['id'] ?>" class="has-text-black" data-value="filename"><? echo $filename ?></a>
                <? } else { ?>
                    <span data-value="filename"><? echo $filename ?></span> <span class="has-text-grey-light">.<? echo $extension; ?></span>
                <? } ?>

            </span>

            <span class="ml-auto icon is-small">
              <i class="fas fa-link" aria-hidden="true"></i>
            </span>

            <div class="dropdown is-hoverable is-right">
                <div class="dropdown-trigger">
                  <button class="button is-small is-ghost is-inverted" aria-haspopup="true"
                          aria-controls="dropdown-menu">
                    <span class="icon is-small">
                      <i class="fas fa-ellipsis-h" aria-hidden="true"></i>
                    </span>
                  </button>
                </div>
                <div class="dropdown-menu" id="dropdown-menu" role="menu">
                    <div class="dropdown-content is-block">
                        <a href="#" class="dropdown-item"
                           onclick="favoriteFile(event, <? echo $file['id'] ?>, <? echo $id === 'favorites' ? true : false ?>)">

                            <span class="icon">
                                <i class="fas fa-star"></i>
                            </span>

                            <span data-value="favorite_text">
                                <? if ($favorite) { ?>
                                    Remover dos favoritos
                                <? } else { ?>
                                    Adicionar aos favoritos
                                <? } ?>
                            </span>
                        </a>

                        <a href="#" class="dropdown-item"
                           onclick="openShare(event, <? echo $file['id'] ?>, '<? echo $file['name'] ?>')">
                            <span class="icon">
                                <i class="fas fa-link"></i>
                            </span>
                            <span>Partilhar</span>
                        </a>

                        <a href="#" class="dropdown-item"
                           onclick="openRename(event, <? echo $file['id'] ?>, '<? echo $file['name'] ?>')">
                            <span class="icon">
                                <i class="fas fa-pen"></i>
                            </span>
                            <span>Renomear</span>
                        </a>

                        <a href="/drive/download?files[]=<? echo $file['id'] ?>" class="dropdown-item">
                            <span class="icon">
                                <i class="fas fa-download"></i>
                            </span>
                            <span>Transferir</span>
                        </a>

                        <a href="#" class="dropdown-item"
                           onclick="openDelete(event, <? echo $file['id'] ?>, '<? echo $file['name'] ?>', <? echo $deleted ?>)">
                            <span class="icon">
                                <i class="fas fa-trash"></i>
                            </span>
                            <span>Eliminar</span>
                        </a>

                        <? if ($deleted) {
                            ?>
                            <a href="#" class="dropdown-item" onclick="restoreFile(<? echo $file['id'] ?>)">
                            <span class="icon">
                                <i class="fas fa-recycle"></i>
                            </span>
                            <span>Restaurar</span>
                        </a>
                            <?
                        } ?>
                    </div>
                </div>
            </div>
        </span>
</td>
<td style="vertical-align: middle; white-space: nowrap;">
    <? if ($size > 0) {
        echo $humanSize;
    } ?>
</td>
<td style="vertical-align: middle; white-space: nowrap;">

    <? echo Utils::humanizeDateDifference(time(), strtotime($file['modified_at'])) ?>
</td>
