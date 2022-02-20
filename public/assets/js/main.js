function setActive($el) {
    $el.classList.add('is-active');
    $el.classList.remove('is-hidden');
}

function setInactive($el) {
    $el.classList.remove('is-active');
    $el.classList.add('is-hidden');
}

/*

    Injects data into an html element

    Example:
    <div id="hello">
        <span data-value="message"></span>
    <div>

    injectData(document.getElementById('hello'), {
        message: 'Hello World'
    });

    Array Example:
    <div id="hello">
        <div data-value="messages">
            <span data-value="messages.text"></span>
        </div>
    <div>

    injectData(document.getElementById('hello'), {
        messages: [
            { text: 'Hello World' },
            { text: 'Hello World2' },
        ]
    });

 */
function injectData(element, data, prefix = '') {
    element && data && Object.keys(data).forEach((key) => {
        const children = element.querySelectorAll(`[data-value="${prefix}${key}"]`);
        const value = data[key];

        children?.forEach((child) => {
            if (Array.isArray(value)) {
                const template = child.firstElementChild;
                template.classList.add('is-hidden');

                child.innerHTML = '';
                child.appendChild(template);

                value.forEach(item => {
                    const copy = template.cloneNode(true);
                    copy.classList.remove('is-hidden');

                    injectData(copy, item, `${prefix}${key}.`);

                    child.appendChild(copy);
                })
                return;
            }

            if (child.tagName === 'INPUT') {
                child.value = value;
            } else if (child.tagName === 'SELECT') {
                child.selectedIndex = Array.prototype.findIndex.call(child.options, f => f.value == value)
            } else {
                child.innerHTML = value;
            }
        })
    })
}

document.addEventListener('DOMContentLoaded', () => {

    // Get all "navbar-burger" elements
    const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);

    // Check if there are any navbar burgers
    if ($navbarBurgers.length > 0) {

        // Add a click event on each of them
        $navbarBurgers.forEach( el => {
            el.addEventListener('click', () => {

                // Get the target from the "data-target" attribute
                const target = el.dataset.target;
                const $target = document.getElementById(target);

                // Toggle the "is-active" class on both the "navbar-burger" and the "navbar-menu"
                el.classList.toggle('is-active');
                $target.classList.toggle('is-active');

            });
        });
    }

});