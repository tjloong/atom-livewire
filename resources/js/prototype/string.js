String.prototype.toDateFormat = function(format) {
    const date = dayjs(this, 'YYYY-MM-DD HH:mm:ss')
    if (date.isValid()) return date.format(format)
}

String.prototype.toDateString = function() {
    const date = dayjs(this, 'YYYY-MM-DD HH:mm:ss')
    if (date.isValid()) return date.format('DD MMM YYYY')
}

String.prototype.toDatetimeString = function() {
    const date = dayjs(this, 'YYYY-MM-DD HH:mm:ss')
    if (date.isValid()) return date.format('DD MMM YYYY h:mm A')
}

String.prototype.toTimeString = function() {
    const date = dayjs(this, 'YYYY-MM-DD HH:mm:ss')
    if (date.isValid()) return date.format('h:mm A')
}

String.prototype.fromNow = function() {
    const date = dayjs(this, 'YYYY-MM-DD HH:mm:ss')
    if (date.isValid()) return date.fromNow()
}

String.prototype.camel = function() {
    return this.toString()
        .replace('-', ' ')
        .replace(/(?:^\w|[A-Z]|\b\w)/g, (word, index) => index === 0 ? word.toLowerCase() : word.toUpperCase())
        .replace(/\s+/g, '');
}

String.prototype.slug = function() {
    const a = 'àáâäæãåāăąçćčđďèéêëēėęěğǵḧîïíīįìıİłḿñńǹňôöòóœøōõőṕŕřßśšşșťțûüùúūǘůűųẃẍÿýžźż·/_,:;'
    const b = 'aaaaaaaaaacccddeeeeeeeegghiiiiiiiilmnnnnoooooooooprrsssssttuuuuuuuuuwxyyzzz------'
    const p = new RegExp(a.split('').join('|'), 'g')

    return this.toString().toLowerCase()
        .replace(/\s+/g, '-') // Replace spaces with -
        .replace(p, c => b.charAt(a.indexOf(c))) // Replace special characters
        .replace(/&/g, '-and-') // Replace & with 'and'
        .replace(/[^\w\-]+/g, '') // Remove all non-word characters
        .replace(/\-\-+/g, '-') // Replace multiple - with single -
        .replace(/^-+/, '') // Trim - from start of text
        .replace(/-+$/, '') // Trim - from end of text
}