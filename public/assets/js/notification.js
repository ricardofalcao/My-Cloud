let notificationInterval = null;
let lastNotificationVariant = null;


function notificationAnimationEnd() {
    const target = document.getElementById('notification');
    setInactive(target)

    target.removeEventListener('animationend', notificationAnimationEnd, false)
};

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

    target.classList.remove('animate__fadeOutDown');
    target.classList.add('animate__fadeInDown');

    target.removeEventListener('animationend', notificationAnimationEnd, false);
    notificationInterval && clearInterval(notificationInterval);
    if (timeout > 0) {
        notificationInterval = setInterval(() => {
            clearNotification();
        }, timeout);
    }
}

function clearNotification() {
    const target = document.getElementById('notification');

    target.classList.add('animate__fadeOutDown');

    target.addEventListener('animationend', notificationAnimationEnd, false);
}

// notifications close button
document.querySelectorAll('.notification .delete').forEach(($delete) => {
    const $notification = $delete.parentNode;

    $delete.addEventListener('click', () => {
        $notification.parentNode.removeChild($notification);
    });
});