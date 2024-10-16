export default (el, { Alpine }) => {
    let els = Array.from(el.querySelectorAll(':scope > [data-sortid]'))
    if (!els.length) els = Array.from(el.querySelectorAll(':scope > [data-id]'))
    
    return els.map(val => (val.getAttribute('data-sortid') || val.getAttribute('data-id')))
}