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
    <div id="drag_overlay">
        <h1>Drag and drop to upload!</h1>
    </div>

    <?php
    View::render('components/drive/navbar.php');
    ?>

    <main class="hero is-fullheight-with-navbar">
        <div class="columns is-gapless is-flex-grow-1">
            <?php
            View::render('components/drive/sidebar.php', [
                'sidebar_current_id' => $id,
                'count' => $count,
            ]);
            ?>


            <div class="column">
                <nav class="breadcrumb ml-4 mt-4" aria-label="breadcrumbs">
                    <ul>
                        <li>
                            <a href="/drive/files">
                                <span class="icon is-small">
                                  <i class="fas fa-home" aria-hidden="true"></i>
                                </span>
                                <span><wbr /></span>
                            </a>
                        </li>
                        <?
                            if (isset($ancestors)) {
                                $len = count($ancestors);
                                foreach($ancestors as $i => $ancestor) {
                                    if ($i == $len - 1) {
                        ?>
                                        <li class="is-active"><a href="#" aria-current="page"><? echo $ancestor['name'] ?></a></li>
                        <?
                                    } else {
                        ?>
                                        <li><a href="/drive/files/<? echo $ancestor['id'] ?>"><? echo $ancestor['name'] ?></a></li>
                        <?
                                    }
                                }
                            }
                        ?>
                    </ul>
                </nav>

                <div class="table-wrapper">
                    <table class="table is-fullwidth">
                        <thead>
                        <tr class="has-text-grey-light">
                            <td style="vertical-align: middle; font-size: 1.4rem;">
                                <input type="checkbox" class="row-checkbox has-text-primary"
                                       onchange="checkboxAll(event.currentTarget.checked)">
                            </td>
                            <td class="py-3">Nome</td>
                            <td class="py-3 pr-4">Tamanho</td>
                        </tr>
                        </thead>

                        <tbody class="is-scrollable">
                        <?php
                        foreach ($files as $index => $file) {
                            $fileId = $file['id'];

                            View::render('components/drive/file.php', [
                                'file' => $file,
                                'index' => $index
                            ]);
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    let lastIndex = -1;

    function check(checkbox, shift) {
        const checkboxes = document.getElementsByClassName('row-checkbox')
        const index = [].indexOf.call(checkboxes, checkbox)

        if (shift && lastIndex != -1) {
            const min = Math.min(lastIndex, index);
            const max = Math.max(lastIndex, index);

            for (let i = min; i <= max; i++) {
                checkboxes[i].checked = checkbox.checked;
            }
        }

        lastIndex = index;
    }

    function checkboxAll(value) {
        const checkboxes = document.getElementsByClassName('row-checkbox')
        for (let i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = value;
        }
    }

    async function restoreFile(fileId) {
        const result = await fetch(`/drive/trash/${fileId}`, {
            method: 'POST',
        })

        if (result.ok) {
            document.location.reload()
        }
    }

    async function deleteFile(fileId, force) {
        const result = await fetch(force ? `/drive/trash/${fileId}` : `/drive/files/${fileId}`, {
            method: 'DELETE',
        })

        if (result.ok) {
            document.location.reload()
        }
    }

    async function favoriteFile(fileId, value) {
        const result = await fetch(`/drive/favorites/${fileId}`, {
            method: value ? 'POST' : 'DELETE',
        })

        if (result.ok) {
            document.location.reload()
        }
    }

    async function uploadFiles(files) {
        const data = new FormData()
        for (let i = 0; i < files.length; i++) {
            data.append('files[]', files[i])
        }

        const result = await fetch(window.location.href, {
            method: 'POST',
            body: data
        })

        if (result.ok) {
            window.location.reload();
        }
    }

    async function createFolder(event) {
        event.preventDefault();

        const data = new FormData()
        data.append('folderName', Date.now().toString())

        const result = await fetch(window.location.href, {
            method: 'POST',
            body: data
        })

        if (result.ok) {
            window.location.reload();
        }
    }

    async function onFileUpload(event) {
        event.preventDefault();

        await uploadFiles(event.target.files);
    }

    async function onFileDrop(event) {
        event.preventDefault();

        const files = [];

        const items = event.dataTransfer.items;
        if (items) {
            for (let i = 0; i < items.length; i++) {
                if (items[i].kind === 'file') {
                    files.push(items[i].getAsFile());
                }
            }
        }

        if (files.length > 0) {
            await uploadFiles(files);
        }
    }

    async function onFileDrag(event) {
        event.preventDefault();

        const overlay = document.getElementById("drag_overlay");
        overlay.style.display = 'flex';
        overlay.style.opacity = '1';
    }
</script>
</body>

</html>
