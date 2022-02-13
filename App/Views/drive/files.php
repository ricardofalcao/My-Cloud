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
                <div class="is-flex is-align-items-center ml-4 mt-4 mb-4">
                    <nav class="breadcrumb mb-0" aria-label="breadcrumbs">
                        <ul>
                            <li>
                                <a href="/drive/files">
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
                                        <li class="is-active"><a href="#"
                                                                 aria-current="page"><? echo $ancestor['name'] ?></a>
                                        </li>
                                        <?
                                    } else {
                                        ?>
                                        <li><a href="/drive/files/<? echo $ancestor['id'] ?>"
                                               class="has-text-weight-bold"><? echo $ancestor['name'] ?></a></li>
                                        <?
                                    }
                                }
                            }
                            ?>
                        </ul>
                    </nav>

                    <div class="dropdown is-hoverable">
                        <div class="dropdown-trigger">
                            <button class="button is-small" aria-haspopup="true" aria-controls="dropdown-menu">
                                <span class="icon is-small">
                                <i class="fas fa-plus" aria-hidden="true"></i>
                                </span>
                            </button>
                        </div>
                        <div class="dropdown-menu" id="dropdown-menu" role="menu">
                            <div class="dropdown-content">
                                <a href="#" class="dropdown-item modal-trigger" data-target="folder-modal">
                                    Create folder
                                </a>
                                <a href="#" class="dropdown-item">
                                    <label class="is-clickable">
                                        Upload files

                                        <input type="file" style="display: none;" multiple
                                               onchange="onFileUpload(event)">
                                    </label>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

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

    <div id="folder-modal" class="modal">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Create new folder</p>
                <button class="delete" aria-label="close"></button>
            </header>

            <section class="modal-card-body">
                <div class="field">
                    <p class="control has-icons-left has-icons-right">
                        <input id="folder-input" class="input" type="name" placeholder="Folder name" required/>
                        <span class="icon is-small is-left">
                          <i class="fas fa-folder"></i>
                        </span>
                    </p>
                </div>
            </section>

            <footer class="modal-card-foot">
                <button class="button is-success" onclick="createFolder(event)">Create</button>
                <button class="button" onclick="closeNearestModal(this)">Cancel</button>
            </footer>
        </div>
    </div>

    <div id="notification" class="notification is-danger is-light is-hidden">
        <button class="delete"></button>
        <span class="notification-message">Hello worlddddddddddddddddddddaaaaaaaaaaaaaaaaaaaaaad</span>
    </div>

    <div id="rename-modal" class="modal">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Rename file</p>
                <button class="delete" aria-label="close"></button>
            </header>

            <section class="modal-card-body">
                <div class="field">
                    <p class="control has-icons-left has-icons-right">
                        <input id="rename-input" class="input" type="name" placeholder="File name" required/>
                        <span class="icon is-small is-left">
                          <i class="fas fa-pen"></i>
                        </span>
                    </p>
                </div>
            </section>

            <footer class="modal-card-foot">
                <button class="button is-success" onclick="renameFile(event)">Rename</button>
                <button class="button" onclick="closeNearestModal(this)">Cancel</button>
            </footer>
        </div>
    </div>
</div>

<script>
    let notificationInterval = null;

    function setNotification(message, timeout = 5000) {
        if (!message) {
            return;
        }

        let text = message;
        if (Array.isArray(message)) {
            text = message.join('<br/>');
        }

        const target = document.getElementById('notification');
        Array.prototype.forEach.call(target.getElementsByClassName('notification-message'), e => e.innerHTML = text);
        setActive(target);

        notificationInterval && clearInterval(notificationInterval);
        notificationInterval = setInterval(() => {
            setInactive(target);
            Array.prototype.forEach.call(target.getElementsByClassName('notification-message'), e => e.innerHTML = '');
        }, timeout);
    }

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
        } else {
            setNotification((await result.json()).errors)
        }
    }

    async function deleteFile(fileId, force) {
        const result = await fetch(force ? `/drive/trash/${fileId}` : `/drive/files/${fileId}`, {
            method: 'DELETE',
        })

        if (result.ok) {
            document.location.reload()
        } else {
            setNotification((await result.json()).errors)
        }
    }

    async function favoriteFile(event, fileId, value) {
        event.preventDefault();
        const result = await fetch(`/drive/favorites/${fileId}`, {
            method: value ? 'POST' : 'DELETE',
        })

        if (result.ok) {
            document.location.reload()
        } else {
            setNotification((await result.json()).errors)
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
        } else {
            setNotification((await result.json()).errors)
        }
    }

    async function createFolder(event) {
        const folderName = document.getElementById("folder-input").value;

        event.preventDefault();

        const data = new FormData()
        data.append('folderName', folderName)

        const result = await fetch(window.location.href, {
            method: 'POST',
            body: data
        })

        if (result.ok) {
            window.location.reload();
            closeNearestModal(event.target);
        } else {
            setNotification((await result.json()).errors)
        }
    }

    let currentRenameId = null;
    let currentRenameExtension = null;

    async function renameFile(event) {
        event.preventDefault();

        if (!currentRenameId) {
            return;
        }

        const fileName = document.getElementById("rename-input").value;

        const result = await fetch(`/drive/files/${currentRenameId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                fileName: currentRenameExtension ? fileName + "." + currentRenameExtension : fileName
            })
        })

        if (result.ok) {
            window.location.reload();
            closeNearestModal(event.target);
        } else {
            setNotification((await result.json()).errors)
        }
    }

    async function openRename(event, fileId, fileName) {
        event.preventDefault();

        const extIndex = fileName.lastIndexOf('.');
        currentRenameExtension = extIndex > 0 ? fileName.substr(extIndex + 1) : null;
        currentRenameId = fileId;

        const input = document.getElementById('rename-input');
        input.value = extIndex > 0 ? fileName.substr(0, extIndex) : fileName;

        setActive(document.getElementById('rename-modal'));
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
