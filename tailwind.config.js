/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    colors: {
        myColor: {
          bg: "#000808",
          semibg: "#001616",
          semibg2: "#002E2E",
          semibg3: "#000d0d",
          text: "#e2f0f0",
          primary: "#93e3e3",
          secondary: "#168888",
          accent: "#38efef",
          yellow: "#cbd117",
        },
        admin: {
          bg: "#060210",
          semibg: "#100a21",
          semibg2: "#0b0222",
          semibg3: "#05010f",
          text: "#e1d3fa",
          primary: "#ac82f0",
          secondary: "#9a1219",
          accent: "#e77e33",
          yellow: "#cbd117",
        },
      },
    extend: {},
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
