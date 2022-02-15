let lastIndex = -1;

function getFileById(id) {
    return document.querySelector(`[data-fileid="${id}"]`)
}

function updateCount(response) {
    injectData(document.getElementById('sidebar'), {
        ...response.count
    })
}

/*

    CHECKBOXES

 */

function getChecked() {
    const checkboxes = document.getElementsByClassName('row-checkbox');
    return Array.prototype.filter.call(checkboxes, (check) => check.checked).map((check) => Number.parseInt(check.getAttribute('data-value'))).filter(v => !!v);
}

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

/*

    RESTORE

 */

async function restoreFile(fileId) {
    const result = await fetch(`/drive/trash/${fileId}`, {
        method: 'POST',
    })

    if (result.ok) {
        const target = getFileById(deleteFileId);
        target?.remove();
    } else {
        setNotification((await result.json()).errors)
    }
}

/*

    FAVORITE

 */

async function favoriteFile(event, fileId, deleteOnRemoval = false) {
    event.preventDefault();

    const target = getFileById(fileId);
    const favoriteTarget = target?.querySelector('.favorite');

    if (!favoriteTarget) {
        return;
    }

    const value = favoriteTarget.classList.contains('is-hidden');

    const result = await fetch(`/drive/favorites/${fileId}`, {
        method: value ? 'POST' : 'DELETE',
    })

    const response = await result.json();
    if (result.ok) {
        target?.querySelector('.favorite')?.classList.toggle('is-hidden');
        injectData(target, {
            favorite_text: value ? 'Remover dos favoritos' : 'Adicionar aos favoritos'
        })

        if (!value && deleteOnRemoval) {
            target?.remove();
        }

        updateCount(response);
    } else {
        setNotification(response.errors)
    }
}

/*

    FILE UPLOAD

 */

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

/*

    NEW FOLDER

 */

async function createFolder(event) {
    event.preventDefault();

    const data = getModalData('folder-modal');
    const folderName = data.input;

    const formData = new FormData()
    formData.append('folderName', folderName)

    const result = await fetch(window.location.href, {
        method: 'POST',
        body: formData
    })

    if (result.ok) {
        window.location.reload();
        closeNearestModal(event.target);
    } else {
        setNotification((await result.json()).errors)
    }
}

function openNewFolder(event) {
    event.preventDefault();

    openModal('folder-modal', {
        input: '',
    });
}

/*

 */

let renameFileId = null;
let renameFileExtension = null;

async function renameFile(event) {
    event.preventDefault();

    if (!renameFileId) {
        return;
    }

    const data = getModalData('rename-modal');
    const fileName = data.input;

    const result = await fetch(`/drive/files/${renameFileId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            fileName: renameFileExtension ? fileName + "." + renameFileExtension : fileName
        })
    })

    const response = (await result.json());
    if (result.ok) {
        const target = getFileById(renameFileId);
        injectData(target, {
            filename: fileName
        })

        closeNearestModal(event.target);
        updateCount(response);
    } else {
        setNotification(response.errors)
    }
}

async function openRename(event, fileId, fileName) {
    event.preventDefault();

    const extIndex = fileName.lastIndexOf('.');
    renameFileExtension = extIndex > 0 ? fileName.substr(extIndex + 1) : null;
    renameFileId = fileId;

    openModal('rename-modal', {
        input: extIndex > 0 ? fileName.substr(0, extIndex) : fileName,
    });
}

/*

    DELETE FILE

 */

let deleteFileId = null;
let deleteForce = false;

async function deleteFile(event) {
    event.preventDefault();

    if (!deleteFileId) {
        return;
    }

    const result = await fetch(deleteForce ? `/drive/trash/${deleteFileId}` : `/drive/files/${deleteFileId}`, {
        method: 'DELETE',
    })

    const response = (await result.json());
    if (result.ok) {
        const target = getFileById(deleteFileId);
        target?.remove();

        closeNearestModal(event.target);
        updateCount(response);
    } else {
        setNotification(response.errors)
    }
}

async function openDelete(event, fileId, fileName, force) {
    event.preventDefault();

    deleteFileId = fileId;
    deleteForce = force;

    openModal('delete-modal', {
        input: [
            `Tem a certeza que deseja eliminar "${fileName}"?`,
            (force ? 'Este ficheiro vai ser eliminado permanentemente!' : 'Este ficheiro vai ser movido para a reciclagem.')
        ].join('<br/>')
    });
}

/*

    SHARE FILE

 */

let shareFileId = null;

async function shareFile(event) {
    event.preventDefault();

    if (!shareFileId) {
        return;
    }

    const data = getModalData('share-modal');
    const username = data.input;
    const type = data.type;

    const formData = new FormData()
    formData.append('username', username)
    formData.append('type', type)

    console.log(username + " - " + type);
    /*const result = await fetch(`/drive/shared/${shareFileId}`, {
        method: 'POST',
    })

    if (result.ok) {
        document.location.reload()
        closeNearestModal(event.target);
    } else {
        setNotification((await result.json()).errors)
    }*/
}

async function openShare(event, fileId, fileName) {
    event.preventDefault();

    shareFileId = fileId;

    const extIndex = fileName.lastIndexOf('.');

    openModal('share-modal', {
        title: extIndex > 0 ? fileName.substr(0, extIndex) : fileName,
        input: '',
        type: 'VIEWER',
        items: [
            {
                user: 'Hello World!'
            },
            {
                user: 'Hello World2!'
            }
        ]
    });
}

/*

    MOVE FILE

 */

async function moveFiles(files, targetId) {
    const result = await fetch(`/drive/files/${targetId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            moves: files
        })
    })

    if (result.ok) {
        window.location.reload();
    } else {
        setNotification((await result.json()).errors)
    }
}

/*

 */

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

    const overlay = document.getElementById("drag_overlay");
    overlay.style.opacity = '0';
    overlay.style.display = 'none';

    if (files.length > 0) {
        await uploadFiles(files);
    }

}

async function onFileDrag(event) {
    event.preventDefault();

    const items = event.dataTransfer.items;
    if (items) {
        for (let i = 0; i < items.length; i++) {
            if (items[i].kind === 'file') {
                const overlay = document.getElementById("drag_overlay");
                overlay.style.display = 'flex';
                overlay.style.opacity = '1';
                break;
            }
        }
    }
}

/*

 */

async function onRowFileDragStart(event, fileId) {
    event.dataTransfer.setData("files",  JSON.stringify([
        fileId,
        ...getChecked()
    ]));
}

async function onRowFileDragOver(event, element, folderId) {
    event.preventDefault();

    let fileIds = event.dataTransfer.getData('files');

    if (fileIds) {
        fileIds = JSON.parse(fileIds);

        if (!fileIds.find(f => f === folderId)) {
            element.classList.add('is-folder-drag');
        }
    }
}

async function onRowFileDragLeave(event, element) {
    event.preventDefault();

    element.classList.remove('is-folder-drag');
}

async function onRowFileDrop(event, element, targetId) {
    event.preventDefault();

    element.classList.remove('is-folder-drag');
    let fileIds = event.dataTransfer.getData('files');

    if (fileIds) {
        fileIds = JSON.parse(fileIds);

        await moveFiles(fileIds, targetId);
    }
}