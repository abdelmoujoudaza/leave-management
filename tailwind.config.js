const defaultTheme = require('tailwindcss/defaultTheme');
const plugin = require('tailwindcss/plugin');

module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', 'Montserrat', ...defaultTheme.fontFamily.sans],
            },
            spacing: {
                '132': '33rem',
            },
            colors: {
                'blue-light': '#0061A6',
                'blue-lightest': '#D6E5F1',
                'blue-dark': '#004677',
                'blue-darkest': '#3DB5E6',
                'gray-light': '#D9D9D9',
                'gray-semilight': '#E5E5E5',
                'gray-lightest': '#F7F7F7',
                "gray-normal": "#959595",
                'gray-dark': '#BABABA',
                'gray-darkest': '#B6B6B6'
            },
            backgroundSize: {
                'half': '50%',
            },
            flex: {
                'product-list-item': '1 1 calc(50% - 6rem)'
            }
        }
    },

    variants: {
        opacity: ['responsive', 'hover', 'focus', 'disabled'],
    },

    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
        plugin(function({ addComponents, theme }) {
            const components = {
                '.form-tick': {
                    '&:checked': {
                        backgroundImage: "url(\"data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='blue' xmlns='http://www.w3.org/2000/svg'%3e%3ccircle cx='8' cy='8' r='5'/%3e%3c/svg%3e\")",
                        backgroundColor: theme('colors.white'),
                        borderColor: '#d2d6dc'
                    }
                },
                '.btn-edit': {
                    backgroundImage: "url(\"data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='22.496' height='19.993' viewBox='0 0 22.496 19.993'%3e%3cpath id='Icon_awesome-edit' class='text-sm' data-name='Icon awesome-edit' d='M15.724,3.253l3.523,3.523a.382.382,0,0,1,0,.539l-8.53,8.53-3.624.4a.76.76,0,0,1-.84-.84l.4-3.624,8.53-8.53A.382.382,0,0,1,15.724,3.253Zm6.327-.894L20.145.452a1.528,1.528,0,0,0-2.156,0L16.607,1.835a.382.382,0,0,0,0,.539L20.13,5.9a.382.382,0,0,0,.539,0l1.383-1.383a1.528,1.528,0,0,0,0-2.156ZM15,13.524V17.5H2.5V5h8.975a.48.48,0,0,0,.332-.137L13.369,3.3a.469.469,0,0,0-.332-.8H1.875A1.875,1.875,0,0,0,0,4.377V18.125A1.875,1.875,0,0,0,1.875,20H15.623A1.875,1.875,0,0,0,17.5,18.125V11.962a.47.47,0,0,0-.8-.332l-1.562,1.562A.48.48,0,0,0,15,13.524Z' transform='translate(0 -0.007)' fill='gray'/%3e%3c/svg%3e\")",
                },
                '.btn-remove': {
                    backgroundImage: "url(\"data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='15.55' height='19.993' viewBox='0 0 15.55 19.993'%3e%3cpath id='Icon_material-delete' class='text-sm' data-name='Icon material-delete' d='M8.611,22.272a2.228,2.228,0,0,0,2.221,2.221h8.886a2.228,2.228,0,0,0,2.221-2.221V8.943H8.611ZM23.05,5.611H19.163L18.052,4.5H12.5L11.388,5.611H7.5V7.832H23.05Z' transform='translate(-7.5 -4.5)' fill='gray'/%3e%3c/svg%3e\")",
                },
                '.flatpickr-day': {
                    '&.selected, &.startRange, &.endRange': {
                        '&, &.inRange, &:focus, &:hover, &.prevMonthDay, &.nextMonthDay': {
                            backgroundColor: theme('colors.blue-light') + '!important',
                            borderColor: theme('colors.blue-light') + '!important',
                        },
                        '&.startRange + .endRange:not(:nth-child(7n+1))': {
                            boxShadow: '-10px 0 0 ' + theme('colors.blue-light') + '!important',
                        }
                    },
                    '&.week.selected': {
                        boxShadow: '-5px 0 0 ' + theme('colors.blue-light') + ', 5px 0 0 ' + theme('colors.blue-light') + '!important',
                    }
                }
            }

            addComponents(components)
        }),
        plugin(function({ addUtilities, theme, config }) {
            const themeColors = theme('colors');
            const individualBorderColors = Object.keys(themeColors).map(colorName => ({
                [`.border-b-${colorName}`]: {
                borderBottomColor: themeColors[colorName]
                },
                [`.border-t-${colorName}`]: {
                borderTopColor:  themeColors[colorName]
                },
                [`.border-l-${colorName}`]: {
                borderLeftColor:  themeColors[colorName]
                },
                [`.border-r-${colorName}`]: {
                borderRightColor:  themeColors[colorName]
                }
            }));

            addUtilities(individualBorderColors);
        }),
    ],
};
