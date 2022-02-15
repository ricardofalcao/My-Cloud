<?php

use Core\View;

if (!isset($id) || !isset($files) || !isset($count)) {
    return;
}

?>

<!DOCTYPE html>
<html lang="en">

<?php

View::render('components/head.php');

?>

<body>
<div id="app" ondrop="onFileDrop(event)" ondragover="onFileDrag(event)">
    <?php
    View::render('components/base.php');
    ?>

    <div id="drag_overlay">
        <h1>Drag and drop to upload!</h1>
    </div>

    <?php
    View::render('components/drive/navbar.php');
    ?>

    <main class="hero is-fullheight-with-navbar">
        <div class="columns is-gapless is-flex-grow-1 «">
            <?php
            View::render('components/drive/sidebar.php', [
                'sidebar_current_id' => $id,
                'count' => $count,
            ]);
            ?>


            <div class="column is-flex is-flex-direction-column">
                <div class="is-flex is-align-items-center ml-4 mt-4 mb-4">
                    <nav class="breadcrumb mb-0" aria-label="breadcrumbs">
                        <ul>
                            <li>
                                <a
                                        href="/drive/files"
                                    <?
                                    echo 'ondragover="onRowFileDragOver(event, this, 0)"';
                                    echo 'ondrop="onRowFileDrop(event, this, 0)"';
                                    echo 'ondragleave="onRowFileDragLeave(event, this)"';
                                    ?>
                                >
                                <span class="icon is-small">
                                  <i class="fas fa-home" aria-hidden="true"></i>
                                </span>
                                    <span><wbr/></span>
                                </a>
                            </li>
                            <?
                            if (isset($ancestors)) {
                                $len = count($ancestors);
                                foreach ($ancestors as $i => $ancestor) {
                                    if ($i == $len - 1) {
                                        ?>
                                        <li
                                                class="is-active"
                                        ><a href="#"
                                            aria-current="page"><? echo $ancestor['name'] ?></a>
                                        </li>
                                        <?
                                    } else {
                                        ?>
                                        <li><a
                                                href="/drive/files/<? echo $ancestor['id'] ?>"
                                                <?
                                                echo 'ondragover="onRowFileDragOver(event, this, ' . $ancestor['id'] . ')"';
                                                echo 'ondrop="onRowFileDrop(event, this, ' . $ancestor['id'] . ')"';
                                                echo 'ondragleave="onRowFileDragLeave(event, this)"';
                                                ?>
                                                    class="has-text-weight-bold"><? echo $ancestor['name'] ?>
                                            </a></li>
                                        <?
                                    }
                                }
                            }
                            ?>
                        </ul>
                    </nav>

                    <div class="dropdown is-hoverable">
                        <div class="dropdown-trigger">
                            <button class="button is-small" style="border-radius: 9999px;" aria-haspopup="true"
                                    aria-controls="dropdown-menu">
                                <span class="icon is-small">
                                <i class="fas fa-plus" aria-hidden="true"></i>
                                </span>
                            </button>
                        </div>
                        <div class="dropdown-menu" id="dropdown-menu" role="menu">
                            <div class="dropdown-content">
                                <a href="#" class="dropdown-item" onclick="openNewFolder(event)">
                                    Criar pasta
                                </a>

                                <label>
                                    <input type="file" style="display: none;" multiple
                                           onchange="onFileUpload(event)">

                                    <a class="dropdown-item is-clickable">
                                        Enviar ficheiros
                                    </a>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="is-flex-grow-1 is-scrollable">
                    <div class="table-container" style="padding-bottom: 12rem;">
                        <table class="table is-fullwidth">
                            <thead>
                            <tr class="has-text-grey-light">
                                <td style="vertical-align: middle; font-size: 1.4rem;">
                                    <input type="checkbox" class="row-checkbox has-text-primary"
                                           onchange="checkboxAll(event.currentTarget.checked)">
                                </td>
                                <td class="py-3" style="width: 99%;">
                                    <!--<span class="icon-text is-align-items-center">
                                        <span>Nome</span>

                                        <form href="/drive/files/">
                                            <a class="button is-white is-small is-rounded ml-1">
                                                <span class="icon is-small has-text-grey-light">
                                                    <i class="fas fa-sort-alpha-down"></i>
                                                </span>
                                            </a>
                                        </form>
                                    </span>-->

                                    Nome
                                </td>
                                <td class="py-3">Tamanho</td>
                                <td class="py-3 pr-4">Modificado</td>
                            </tr>
                            </thead>

                            <tbody>

                            <? foreach ($files as $index => $file) { ?>

                                <tr
                                        class="datatable-item is-clickable"
                                        data-fileid="<? echo $file['id'] ?>"
                                        draggable="true"
                                        ondragstart="onRowFileDragStart(event, <? echo $file['id'] ?>)"
                                    <?
                                    if ($file['type'] === 'FOLDER') {
                                        echo 'ondragover="onRowFileDragOver(event, this, ' . $file['id'] . ')"';
                                        echo 'ondrop="onRowFileDrop(event, this, ' . $file['id'] . ')"';
                                        echo 'ondragleave="onRowFileDragLeave(event, this)"';
                                    } else {

                                    }
                                    ?>
                                >

                                    <?
                                    View::render('components/drive/file.php', [
                                        'file' => $file,
                                        'id' => $id,
                                    ]);
                                    ?>

                                </tr>

                            <? } ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div id="folder-modal" class="modal">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Criar nova pasta</p>
                <button class="delete" aria-label="close"></button>
            </header>

            <section class="modal-card-body">
                <div class="field">
                    <p class="control has-icons-left has-icons-right">
                        <input data-value="input" class="input" type="name" placeholder="Folder name" required/>
                        <span class="icon is-small is-left">
                          <i class="fas fa-folder"></i>
                        </span>
                    </p>
                </div>
            </section>

            <footer class="modal-card-foot is-justify-content-right">
                <button class="button" onclick="closeNearestModal(this)">Cancelar</button>
                <button class="button is-primary" onclick="createFolder(event)">Criar</button>
            </footer>
        </div>
    </div>

    <div id="rename-modal" class="modal">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Renomear ficheiro</p>
                <button class="delete" aria-label="close"></button>
            </header>

            <section class="modal-card-body">
                <div class="field">
                    <p class="control has-icons-left has-icons-right">
                        <input data-value="input" class="input" type="name" placeholder="File name" required/>

                        <span class="icon is-small is-left">
                          <i class="fas fa-pen"></i>
                        </span>
                    </p>
                </div>
            </section>

            <footer class="modal-card-foot is-justify-content-right">
                <button class="button" onclick="closeNearestModal(this)">Cancelar</button>
                <button class="button is-primary" onclick="renameFile(event)">Renomear</button>
            </footer>
        </div>
    </div>

    <div id="share-modal" class="modal">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Partilhar '<span data-value="title"></span>'</p>
                <button class="delete" aria-label="close"></button>
            </header>

            <section class="modal-card-body">
                <div class="field has-addons">
                    <p class="control has-icons-left is-expanded">
                        <input data-value="input" class="input" type="name" placeholder="Nome de utilizador" required/>

                        <span class="icon is-small is-left">
                          <i class="fas fa-user"></i>
                        </span>
                    </p>
                    <p class="control">
                        <span class="select">
                          <select data-value="type">
                            <option value="VIEWER">Visualizador</option>
                            <option value="EDITOR">Editor</option>
                          </select>
                        </span>
                    </p>
                </div>

                <div class="field is-grouped is-grouped-multiline mt-4" data-value="items">
                    <div class="control">
                        <div class="tags has-addons">
                            <a class="tag is-primary" data-value="items.user"></a>
                            <a class="tag is-delete"></a>
                        </div>
                    </div>
                </div>
            </section>

            <footer class="modal-card-foot is-justify-content-right">
                <button class="button" onclick="closeNearestModal(this)">Cancelar</button>
                <button class="button is-primary" onclick="shareFile(event)">Partilhar</button>
            </footer>
        </div>
    </div>

    <div id="delete-modal" class="modal">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Eliminar ficheiro</p>
                <button class="delete" aria-label="close"></button>
            </header>

            <section class="modal-card-body">
                <p data-value="input"></p>
            </section>

            <footer class="modal-card-foot is-justify-content-right">
                <button class="button" onclick="closeNearestModal(this)">Cancelar</button>
                <button class="button is-danger" onclick="deleteFile(event)">Eliminar</button>
            </footer>
        </div>
    </div>
</div>

<script src="/assets/js/file.js"></script>
<script src="/assets/js/modal.js"></script>

</body>

</html>
