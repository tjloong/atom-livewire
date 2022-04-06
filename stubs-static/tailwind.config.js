const colors = require('tailwindcss/colors')
const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
    mode: 'jit',
    
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './vendor/jiannius/atom-livewire/resources/**/*.js',
        './vendor/jiannius/atom-livewire/resources/**/*.blade.php',
        './vendor/jiannius/atom-livewire/src/Components/**/*.php',
    ],
    
    theme: {
        screens: {
            'sm': '640px',
            'md': '768px',
            'lg': '1024px',
            'xl': '1280px',
            '2xl': '1536px',
            '3xl': '2000px',
        },
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
    
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
}
