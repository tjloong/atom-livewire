export default (message) => {
    dispatchEvent(new CustomEvent('alert', { bubbles: true, detail: message }))
}