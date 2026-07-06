/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
    ],
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                primary: {
                    DEFAULT: '#2B6B47',
                    50: '#F0F7F3',
                    100: '#DCEBE3',
                    200: '#B8D7C7',
                    300: '#8DBDA5',
                    400: '#5C9E7D',
                    500: '#3D8B63',
                    600: '#2B6B47',
                    700: '#1F5236',
                    800: '#153B27',
                    900: '#0C2418',
                },
                secondary: {
                    DEFAULT: '#E8A838',
                    50: '#FEF8ED',
                    100: '#FCEDD1',
                    200: '#F9DCA3',
                    300: '#F5C56B',
                    400: '#F1B44A',
                    500: '#E8A838',
                    600: '#D48D1E',
                    700: '#B07417',
                    800: '#8C5D14',
                    900: '#6B4710',
                },
            },
            fontFamily: {
                heading: ['Playfair Display', 'serif'],
                body: ['Poppins', 'sans-serif'],
            },
            maxWidth: {
                'container': '1124px',
            },
            spacing: {
                '18': '4.5rem',
                '22': '5.5rem',
            },
            borderRadius: {
                '4xl': '2rem',
            },
            boxShadow: {
                'card': '0 2px 16px rgba(30, 80, 50, 0.12)',
                'card-hover': '0 8px 32px rgba(30, 80, 50, 0.12)',
            },
            keyframes: {
                slideDown: {
                    from: {
                        opacity: '0',
                        transform: 'translateX(-50%) translateY(-20px)',
                    },
                    to: {
                        opacity: '1',
                        transform: 'translateX(-50%) translateY(0)',
                    },
                },
                pulseWa: {
                    '0%': { transform: 'scale(1)' },
                    '50%': { transform: 'scale(1.1)' },
                    '100%': { transform: 'scale(1)' },
                },
                slide: {
                    from: { transform: 'translateX(0)' },
                    to: { transform: 'translateX(-100%)' },
                },
            },
            animation: {
                'slide-down': 'slideDown 0.4s ease',
                'pulse-wa': 'pulseWa 2s infinite',
                'slide': 'slide 30s linear infinite',
            },
        },
    },
    plugins: [
        require('@tailwindcss/typography'),
    ],
};
