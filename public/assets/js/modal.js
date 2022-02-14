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

function openModal(modalId, data = null) {
    const target = document.getElementById(modalId);

    if (target) {
        data && Object.keys(data).forEach((k) => {
            const value = data[k];
            const child = target.querySelector(`[data-value="${k}"]`);

            if (child.tagName === 'INPUT') {
                child.value = value;
            } else {
                child.innerHTML = value;
            }
        })

        setActive(target);
    }
}

function getModalData(modalId) {
    const target = document.getElementById(modalId);

    if (target) {
        const output = {};

        target.querySelectorAll(`[data-value]`).forEach((element) => {
            if (element.tagName === 'INPUT') {
                output[element.getAttribute('data-value')] = element.value;
            } else {
                output[element.getAttribute('data-value')] = element.innerHTML;
            }
        });

        return output;
    }
}

// Add a click event on various child elements to close the parent modal
document.querySelectorAll('.modal-background, .modal-close, .modal-card-head .delete').forEach(($close) => {
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