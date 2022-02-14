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

let currentFileId = null;
let currentFileExtension = null;

async function renameFile(event) {
    event.preventDefault();

    if (!currentFileId) {
        return;
    }

    const fileName = document.getElementById("rename-input").value;

    const result = await fetch(`/drive/files/${currentFileId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            fileName: currentFileExtension ? fileName + "." + currentFileExtension : fileName
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
    currentFileExtension = extIndex > 0 ? fileName.substr(extIndex + 1) : null;
    currentFileId = fileId;

    const input = document.getElementById('rename-input');
    input.value = extIndex > 0 ? fileName.substr(0, extIndex) : fileName;

    setActive(document.getElementById('rename-modal'));
}

let forceDelete = false;

async function deleteFile(event) {
    event.preventDefault();

    if (!currentFileId) {
        return;
    }

    const result = await fetch(forceDelete ? `/drive/trash/${currentFileId}` : `/drive/files/${currentFileId}`, {
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

    const extIndex = fileName.lastIndexOf('.');
    currentFileId = fileId;
    forceDelete = force;

    const input = document.getElementById('delete-input');

    input.innerHTML = `Tem a certeza que deseja eliminar "${fileName}"?<br/>`
    input.innerHTML += force ? 'Este ficheiro vai ser eliminado permanentemente!' : 'Este ficheiro vai ser movido para a reciclagem.';

    setActive(document.getElementById('delete-modal'));
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