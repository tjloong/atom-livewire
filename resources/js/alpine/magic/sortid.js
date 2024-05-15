export default (el, { Alpine }) => {
    return Array.from(el.querySelectorAll(':scope > [data-id]')).map(val => (val.getAttribute('data-id')))
}