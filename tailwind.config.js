/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
  ],
  theme: {
    extend: {
      colors: {
        primary: "#1737c8",
        surface: "#f3f3f4",
        "surface-container-low": "#f3f3f4",
        "surface-container-lowest": "#ffffff",
        "on-surface": "#1a1c1c",
        outline: "#747688"
      }
    },
  },
  plugins: [],
}