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

    target.classList.remove('animate__fadeOutDown');
    target.classList.add('animate__fadeInDown');

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

    const onAnimationEnd = () => {
        setInactive(target)

        target.removeEventListener('animationend', onAnimationEnd, false)
    };

    target.addEventListener('animationend', onAnimationEnd, false);
}

// notifications close button
document.querySelectorAll('.notification .delete').forEach(($delete) => {
    const $notification = $delete.parentNode;

    $delete.addEventListener('click', () => {
        $notification.parentNode.removeChild($notification);
    });
});