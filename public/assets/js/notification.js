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