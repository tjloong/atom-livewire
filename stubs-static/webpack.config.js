const path = require('path');

module.exports = {
    resolve: {
        alias: {
            '@': path.resolve('resources/js'),
            '@atom': path.resolve('vendor/jiannius/atom-livewire'),
            '@node': path.resolve('node_modules'),
        },
    },
};