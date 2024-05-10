export default (el, { Alpine }) => {
    return Array.from(el.querySelectorAll('[data-id]')).map(val => (val.getAttribute('data-id')))
}