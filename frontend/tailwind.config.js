const { addDynamicIconSelectors } = require('@iconify/tailwind');
/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}", // Ya está configurado para incluir todos tus archivos de Vue, JS, TS, JSX, y TSX.
    './src/*.html',
    "./node_modules/flowbite/**/*.js" // Agrega esta línea para que también contemple los archivos HTML dentro de `src/`
  ],
  theme: {
    extend: {
      colors: {
        mainBlack: '#100303',
        background: '#FEFBF6',
        text: '#3D3C42',
        buttonTitle: '#7F56D9',
        bag: 'F3F4F6',
      },
      backgroundImage: {
        'gcolor': 'radial-gradient(circle, rgba(148,187,233,1) 15%, rgba(245,167,255,1) 72%)',
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'), // Mantén este plugin si lo necesitas
    addDynamicIconSelectors(),
    require('flowbite/plugin')     // Agrega esta línea para usar la funcionalidad dinámica de Iconify
  ],
}
