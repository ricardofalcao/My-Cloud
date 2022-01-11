<?php
use Core\View;

View::render('components/drive/base.php');

$cloud_files = array_map(function($val) {
    $path_parts = pathinfo($val['name']);
    $folder = $val['type'] === 'FOLDER';

    return new CloudFile($path_parts['filename'], $folder ? '' : $path_parts['extension'], $val['size'], $folder);
}, $files);

?>

<!DOCTYPE html>
<html lang="en">

<?php

View::render('components/head.php');

?>

<body>
    <div id="app">
        <?php
            View::render('components/drive/navbar.php');
        ?>

        <main>
            <?php
                View::render('components/drive/sidebar.php', [
                    'sidebar_current_id' => 'files'
                ]);
            ?>
            

            <div class="content">
                <table class="datatable" cellspacing="0" rowspacing="0">
                    <thead class="datatable-header">
                        <tr>
                            <th class="file-checkbox"><input type="checkbox"></th>
                            <th class="file-icon"></th>
                            <th class="file-name">Nome</th>
                            <th class="file-options"></th>
                            <th class="file-size">Tamanho</th>
                        </tr>
                    </thead>
                    <tbody class="datatable-body">
                        <?php 
                            foreach($cloud_files as $file) {
                        ?>

                        <tr class="datatable-item">
                            <td class="file-checkbox"><input type="checkbox"></td>
                            <td class="file-icon">
                                <i class="fas fa-<? echo $file->icon ?>"></i>
                            </td>
                            <td class="file-name"><? echo $file->name ?><span class="file-extension"><? if (!$file->folder) echo '.' . $file->extension; ?></span></td>
                            <td class="file-options">
                                <i class="fas fa-ellipsis-h"></i>

                                <ul class="dropdown">
                                    <li class="dropdown-link">
                                        <a href="#">
                                            <i style="color: yellow;" class="fas fa-star"></i>
                                            Adicionar aos favoritos
                                        </a>
                                    </li>
                                    <li class="dropdown-link">
                                        <a href="#">
                                            <i class="fas fa-pen"></i>
                                            Renomear
                                        </a>
                                    </li>
                                    <li class="dropdown-link">
                                        <a href="#">
                                            <i class="fas fa-download"></i>
                                            Transferir
                                        </a>
                                    </li>
                                    <li class="dropdown-link">
                                        <a href="#">
                                            <i class="fas fa-trash"></i>
                                            Eliminar
                                        </a>
                                    </li>
                                </ul>
                            </td>
                            <td class="file-size"><? echo $file->size ?></td>
                        </tr>

                        <?php
                            }
                        ?>
                    </tbody>
                </table>
            </div>

            <label class="fab">
                <i class="fas fa-plus"></i>

                <input type="file">
            </label>
        </main>
    </div>
</body>

</html>
