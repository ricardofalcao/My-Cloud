<?php

use Core\Request;
use Core\Utils;

if (!isset($file)) {
    return;
}

$me = Request::get('userId');

$folder = $file['type'] === 'FOLDER';
$favorite = $file['state'] === 'FAVORITE';
$deleted = $file['state'] === 'DELETED';

$path_parts = pathinfo($file['name']);
$filename = $path_parts['filename'];
$extension = $folder ? '' : $path_parts['extension'];

$icon = Utils::iconFromExtension($extension);

$size = $file['size'];
$humanSize = Utils::humanizeBytes($size);

$owned = $me === $file['owner_id'];
$accesses = array_key_exists('accesses', $file) ? json_encode($file['accesses']) : '[]';

?>

<td style="vertical-align: middle; font-size: 1.4rem; white-space: nowrap;">
    <input type="checkbox" data-value="<?php echo $file['id'] ?>" class="row-checkbox has-text-primary" onclick="check(this, event.shiftKey)">
</td>

<td>
        <span class="icon-text is-flex is-align-items-center">
            <span class="icon mr-3 is-relative">
                <i class="fas fa-lg fa-<?php echo $icon ?> <?php echo $folder ? 'has-text-primary' : '' ?> "></i>

                <i style="color: #f6d16b; position: absolute; top: 0; right: -4px;"
                   class="favorite fas fa-star is-size-7 <?php echo $favorite ? '' : 'is-hidden' ?>"></i>
            </span>

            <span class="py-3 row-name">

                <?php if ($folder) { ?>
                    <a href="drive/files/<?php echo $file['id'] ?>" class="has-text-black" data-value="filename"><?php echo $filename ?></a>
                <?php } else { ?>
                    <span data-value="filename"><?php echo $filename ?></span> <span class="has-text-grey-light">.<?php echo $extension; ?></span>
                <?php } ?>

            </span>

            <span class="ml-auto"></span>

            <?php if (array_key_exists('accesses', $file)) { ?>
            <span class="icon is-small">
              <i class="fas fa-link" aria-hidden="true"></i>
            </span>
            <?php } ?>

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
                        <?php if ($owned) { ?>
                            <a href="#" class="dropdown-item"
                               onclick="favoriteFile(event, <?php echo $file['id'] ?>)">

                                <span class="icon">
                                    <i class="fas fa-star"></i>
                                </span>

                                <span data-value="favorite_text">
                                    <?php if ($favorite) { ?>
                                        Remover dos favoritos
                                    <?php } else { ?>
                                        Adicionar aos favoritos
                                    <?php } ?>
                                </span>
                            </a>

                            <a href="#" class="dropdown-item"
                                   onclick="openShare(event, <?php echo $file['id'] ?>)">
                            <span class="icon">
                                <i class="fas fa-link"></i>
                            </span>
                            <span>Partilhar</span>
                        </a>
                        <?php } ?>

                        <a href="#" class="dropdown-item"
                           onclick="openRename(event, <?php echo $file['id'] ?>)">
                            <span class="icon">
                                <i class="fas fa-pen"></i>
                            </span>
                            <span>Renomear</span>
                        </a>

                        <a href="#" onclick="downloadFiles(event, [<?php echo $file['id'] ?>])" class="dropdown-item">
                            <span class="icon">
                                <i class="fas fa-download"></i>
                            </span>
                            <span>Transferir</span>
                        </a>

                        <?php if ($owned) { ?>

                            <a href="#" class="dropdown-item"
                               onclick="openDelete(event, <?php echo $file['id'] ?>)">
                            <span class="icon">
                                <i class="fas fa-trash"></i>
                            </span>
                            <span>Eliminar</span>
                        </a>

                        <?php } ?>

                        <?php if ($deleted) {
                            ?>
                            <a href="#" class="dropdown-item" onclick="restoreFile(<?php echo $file['id'] ?>)">
                            <span class="icon">
                                <i class="fas fa-recycle"></i>
                            </span>
                            <span>Restaurar</span>
                        </a>
                            <?php
                        } ?>
                    </div>
                </div>
            </div>
        </span>
</td>
<td style="vertical-align: middle; white-space: nowrap;">
    <?php if ($size > 0) {
        echo $humanSize;
    } ?>
</td>
<td style="vertical-align: middle; white-space: nowrap;">

    <?php echo Utils::humanizeDateDifference(time(), strtotime($file['modified_at'])) ?>
</td>
