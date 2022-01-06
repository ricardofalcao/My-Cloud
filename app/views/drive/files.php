<?php

$navbar_current_id = 'index';

class CloudFile {
    public $icon;
    public $name;
    public $extension;
    public $size;

    public function __construct(string $icon, string $name, string $extension, string $size) {
        $this->icon = $icon;
        $this->name = $name;
        $this->extension = $extension;
        $this->size = $size;
    }
}

$cloud_files = array(
    new CloudFile('pdf', 'Apresentação', 'pdf', '350 KB'),
    new CloudFile('word', 'Documento', 'word', '25 MB'),
);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="./favicon.ico">

    <title>My Cloud</title>

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">

    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/app.css">
</head>

<body>
    <div id="app">
        <?php
            include 'components/navbar.php'
        ?>

        <main>
            <?php
                include 'components/sidebar.php'
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
                                <i class="fas fa-file-<? echo $file->icon ?>"></i>
                            </td>
                            <td class="file-name"><? echo $file->name ?><span class="file-extension">.<? echo $file->extension ?></span></td>
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
