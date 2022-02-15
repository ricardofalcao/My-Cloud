let notificationInterval = null;
let lastNotificationVariant = null;

function setNotification(message, variant = 'is-danger', timeout = 5000) {
    if (!message) {
        return;
    }

    let text = message;
    if (Array.isArray(message)) {
        text = message.join('<br/>');
    }

    const target = document.getElementById('notification');
    Array.prototype.forEach.call(target.getElementsByClassName('notification-message'), e => e.innerHTML = text);

    if (lastNotificationVariant) {
        target.classList.remove(lastNotificationVariant);
    }

    lastNotificationVariant = variant;
    target.classList.add(lastNotificationVariant);

    setActive(target);

    notificationInterval && clearInterval(notificationInterval);
    notificationInterval = setInterval(() => {
        setInactive(target);
        Array.prototype.forEach.call(target.getElementsByClassName('notification-message'), e => e.innerHTML = '');
    }, timeout);
}

// notifications close button
document.querySelectorAll('.notification .delete').forEach(($delete) => {
    const $notification = $delete.parentNode;

    $delete.addEventListener('click', () => {
        $notification.parentNode.removeChild($notification);
    });
});