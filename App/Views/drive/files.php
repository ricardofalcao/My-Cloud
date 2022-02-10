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

    <main>
        <?php
        View::render('components/drive/sidebar.php', [
            'sidebar_current_id' => $id,
            'count' => $count,
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
                <tbody class="datatable-body" id="datatable-content">
                <?php
                foreach ($files as $file) {
                    $fileId = $file['id'];

                    View::render('components/drive/file.php', [
                        'file' => $file
                    ]);
                }
                ?>
                </tbody>
            </table>
        </div>

        <label class="fab">
            <i class="fas fa-plus"></i>

            <input type="file" multiple onchange="onFileUpload(event)">
        </label>
    </main>
</div>

<script>
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
