export default () => {
    return subject => (
        new Promise((resolve, reject) => {
            navigator.clipboard.writeText(subject)
            resolve()
        })
    )
}