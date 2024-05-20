export default () => {
    return subject => (navigator.clipboard.writeText(subject))
}