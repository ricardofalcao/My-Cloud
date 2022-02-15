
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
        const child = element.querySelector(`[data-value="${prefix}${key}"]`);
        const value = data[key];

        if (child) {
            if (Array.isArray(value)) {
                console.log(child)
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
        }
    })
}