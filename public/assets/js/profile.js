async function updateName(event) {
    event.preventDefault();

    const name = event.target.elements['name']?.value;
    const formData = new FormData();
    formData.append("name", name);

    const result = await fetch(`/dashboard/user/profile/name`, {
        method: 'POST',
        body: formData
    })

    if (result.ok) {
        setNotification('Nome atualizado!', 'is-success');
    } else {
        setNotification((await result.json()).errors)
    }
}