// Functions to open and close a modal
function setActive($el) {
    $el.classList.add('is-active');
    $el.classList.remove('is-hidden');
}

function setInactive($el) {
    $el.classList.remove('is-active');
    $el.classList.add('is-hidden');
}

function closeAllModals() {
    (document.querySelectorAll(".modal") || []).forEach(($modal) => {
        setInactive($modal);
    });
}

function closeNearestModal(me) {
    const target = me.closest('.modal');
    target && setInactive(target);
}

document.addEventListener('DOMContentLoaded', () => {
    // Add a click event on buttons to open a specific modal
    (document.querySelectorAll(".modal-trigger") || []).forEach(($trigger) => {
        const modal = $trigger.dataset.target;
        const $target = document.getElementById(modal);

        $trigger.addEventListener('click', () => {
            setActive($target);
        });
    });

    // Add a click event on various child elements to close the parent modal
    (document.querySelectorAll('.modal-background, .modal-close, .modal-card-head .delete') || []).forEach(($close) => {
        $close.addEventListener('click', () => {
            closeNearestModal($close)
        });
    });

    // Add a keyboard event to close all modals
    document.addEventListener('keydown', (event) => {
        const e = event || window.event;

        if (e.keyCode === 27) { // Escape key
            closeAllModals();
        }
    });

    // notifications close button
    (document.querySelectorAll('.notification .delete') || []).forEach(($delete) => {
        const $notification = $delete.parentNode;

        $delete.addEventListener('click', () => {
            $notification.parentNode.removeChild($notification);
        });
    });
});