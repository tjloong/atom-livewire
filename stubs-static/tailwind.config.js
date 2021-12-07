const colors = require('tailwindcss/colors')
const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
    mode: 'jit',
    
    darkMode: false, // or 'media' or 'class'
    
    purge: {
        content: [
            './resources/**/*.blade.php',
            './resources/**/*.js',
            './vendor/jiannius/atom-livewire/resources/**/*.js',
            './vendor/jiannius/atom-livewire/resources/**/*.blade.php',
            './vendor/jiannius/atom-livewire/src/Components/**/*.php',
        ],
    },
    
    theme: {
        extend: {
            colors: {
                theme: {
                    light: '#ede0ff',
                    DEFAULT: '#9f5afd',
                    dark: '#3b028a',
                },
            },
            borderColor: theme => ({
                DEFAULT: theme('colors.gray.200', 'currentColor'),
            }),
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            boxShadow: theme => ({
                outline: '0 0 0 2px ' + theme('colors.blueGray.500'),
            }),
            fill: theme => theme('colors'),
        },
    },

    variants: {
        extend: {
            fill: ['focus', 'group-hover'],
        },
    },
    
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
}
