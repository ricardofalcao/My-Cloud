let lastIndex = -1;

/*

    CHECKBOXES

 */

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
        document.location.reload()
    } else {
        setNotification((await result.json()).errors)
    }
}

/*

    FAVORITE

 */

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

    const fileName = document.getElementById("rename-input").value;

    const result = await fetch(`/drive/files/${renameFileId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            fileName: renameFileExtension ? fileName + "." + renameFileExtension : fileName
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

    if (result.ok) {
        document.location.reload()
        closeNearestModal(event.target);
    } else {
        setNotification((await result.json()).errors)
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