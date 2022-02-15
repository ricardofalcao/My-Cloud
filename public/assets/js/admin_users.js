

let deleteUserId = null;
let deleteForce = false;

async function deleteUser(event) {
    event.preventDefault();

    if (!deleteUserId) {
        return;
    }

    const result = await fetch(`/dashboard/admin/users/${deleteUserId}`, {
        method: 'DELETE',
    })

    const response = (await result.json());

    if (result.ok) {
        window.location.reload();
    } else {
        setNotification(response.errors)
    }
}

async function openDelete(event, userId, userName) {
    event.preventDefault();

    deleteUserId = userId;

    openModal('delete-modal', {
        username: userName
    });
}