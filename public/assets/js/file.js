/*

 */

let lastIndex = -1;

function getFileElementById(id) {
    return document.querySelector(`[data-fileid="${id}"]`)
}

function updateCount(response) {
    response?.count && injectData(document.getElementById('sidebar'), {
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
    const file = files[fileId];

    if (!file) {
        return;
    }

    setNotification(`Restaurando '${file.name}'...`, 'is-info', -1);

    const result = await fetch(`drive/trash/${fileId}`, {
        method: 'POST',
    })

    const response = (await result.json());
    if (result.ok) {
        const target = getFileElementById(fileId);
        target?.remove();

        updateCount(response);
        setNotification('Sucesso', 'is-success');
    } else {
        setNotification(response.errors)
    }
}

/*

    FAVORITE

 */

async function favoriteFile(event, fileId) {
    event.preventDefault();
    const file = files[fileId];

    if (!file) {
        return;
    }

    const target = getFileElementById(fileId);
    const favoriteTarget = target?.querySelector('.favorite');

    if (!favoriteTarget) {
        return;
    }

    const value = file.state !== 'FAVORITE';

    const result = await fetch(`drive/favorites/${fileId}`, {
        method: value ? 'POST' : 'DELETE',
    })

    const response = await result.json();
    if (result.ok) {
        if (value) {
            target?.querySelector('.favorite')?.classList.remove('is-hidden');
        } else {
            target?.querySelector('.favorite')?.classList.add('is-hidden');
        }

        injectData(target, {
            favorite_text: value ? 'Remover dos favoritos' : 'Adicionar aos favoritos'
        })

        if (!value && routeId === 'favorites') {
            target?.remove();
            delete files[fileId];
        } else {
            files[fileId] = response.file;
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

    setNotification(files.length > 1 ? 'Enviando ficheiros...' : `Enviando '${files[0].name}'...`, 'is-info', -1);

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

    setNotification(`Criando pasta '${folderName}'`, 'is-info', -1);

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

    const file = files[renameFileId];
    if (!file) {
        return;
    }

    const data = getModalData('rename-modal');
    const fileName = data.input;

    setNotification(`Renomeando ficheiro para '${fileName}'...`, 'is-info', -1);
    const result = await fetch(`drive/files/${renameFileId}`, {
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
        const target = getFileElementById(renameFileId);
        injectData(target, {
            filename: fileName
        })

        files[renameFileId] = response.file;
        closeNearestModal(event.target);
        setNotification('Sucesso', 'is-success');
    } else {
        setNotification(response.errors)
    }
}

async function openRename(event, fileId) {
    event.preventDefault();

    const file = files[fileId];
    if (!file) {
        return;
    }

    const extIndex = file.name.lastIndexOf('.');
    renameFileExtension = extIndex > 0 ? file.name.substr(extIndex + 1) : null;
    renameFileId = fileId;

    openModal('rename-modal', {
        input: extIndex > 0 ? file.name.substr(0, extIndex) : file.name,
    });
}

/*

    DELETE FILE

 */

let deleteFileId = null;

async function deleteFile(event) {
    event.preventDefault();

    if (!deleteFileId) {
        return;
    }

    const file = files[deleteFileId];
    if (!file) {
        return;
    }

    setNotification(`Eliminando '${file.name}'...`, 'is-info', -1);

    const deleteForce = file.type === 'DELETED';
    const result = await fetch(deleteForce ? `drive/trash/${deleteFileId}` : `drive/files/${deleteFileId}`, {
        method: 'DELETE',
    })

    const response = (await result.json());
    if (result.ok) {
        const target = getFileElementById(deleteFileId);
        target?.remove();

        updateCount(response);
        delete files[deleteFileId];

        closeNearestModal(event.target);
        setNotification('Sucesso', 'is-success');
    } else {
        setNotification(response.errors)
    }
}

async function openDelete(event, fileId) {
    event.preventDefault();

    const file = files[fileId];
    if (!file) {
        return;
    }

    deleteFileId = fileId;

    openModal('delete-modal', {
        input: [
            `Tem a certeza que deseja eliminar "${file.name}"?`,
            (force ? 'Este ficheiro vai ser eliminado permanentemente!' : 'Este ficheiro vai ser movido para a reciclagem.')
        ].join('<br/>')
    });
}

/*

    DOWNLOAD FILE

 */

async function downloadFiles(event, fileIds) {
    event.preventDefault();

    setNotification(fileIds.length > 1 ? 'Transferindo ficheiros...' : `Transferindo '${files[fileIds[0]].name}'...`, 'is-info', -1);

    const fileQuery = fileIds.map(id => `files[]=${id}`).join('&');
    const result = await fetch( `drive/download?${fileQuery}`);

    if (result.ok) {
        const blob = await result.blob();

        const header = result.headers.get('Content-Disposition');
        const parts = header.split(';');

        const a = Object.assign(document.createElement('a'), {
            href: window.URL.createObjectURL(blob),
            download: parts[1].split('=')[1],
        })

        a.click();

        clearNotification();
    } else {
        setNotification((await result.json()).errors)
    }
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

    const file = files[shareFileId];
    if (!file) {
        return;
    }

    const data = getModalData('share-modal');
    const username = data.input;
    const type = data.type;

    const formData = new FormData()
    formData.append('username', username)
    formData.append('type', type)

    const result = await fetch(`drive/shared/${shareFileId}`, {
        method: 'POST',
        body: formData,
    })

    const response = (await result.json());
    if (result.ok) {
        console.log(response);
    } else {
        setNotification(response.errors)
    }
}

async function openShare(event, fileId) {
    event.preventDefault();

    const file = files[fileId];
    if (!file) {
        return;
    }

    shareFileId = fileId;

    const extIndex = file.name.lastIndexOf('.');

    openModal('share-modal', {
        title: extIndex > 0 ? file.name.substr(0, extIndex) : file.name,
        input: '',
        type: 'VIEWER',
        accesses: file.accesses || [],
    });
}

/*

    MOVE FILE

 */

async function moveFiles(fileIds, targetId) {
    setNotification(fileIds.length > 1 ? 'Movendo ficheiros...' : `Movendo '${files[fileIds[0]]?.name}'...`, 'is-info', -1);;

    const result = await fetch(`drive/files/${targetId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            moves: fileIds
        })
    })

    if (result.ok) {
        fileIds.forEach((fileId) => {
            const target = getFileElementById(fileId);
            target?.remove();
        })

        setNotification('Sucesso', 'is-success');
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
        fileIds = JSON.parse(fileIds).filter(f => f !== targetId);

        if (fileIds.length > 0) {
            await moveFiles(fileIds, targetId);
        }
    }
}