import { addDynamicIconSelectors } from '@iconify/tailwind';
import forms from '@tailwindcss/forms';
import flowbitePlugin from 'flowbite/plugin';

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
    './src/*.html',
    "./node_modules/flowbite/**/*.js"
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
    forms,
    addDynamicIconSelectors(),
    flowbitePlugin,
  ],
};
