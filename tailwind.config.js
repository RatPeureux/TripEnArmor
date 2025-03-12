/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./**/*.php", "./**/*.html"],
	  safelist: [
    {
      pattern: /bg-(red|green|blue|orange)-(100|500|700)/, // You can display all the colors that you need
      variants: ['lg', 'hover', 'focus', 'lg:hover'],      // Optional
    },
  ],
  theme: {
    extend: {
      fontFamily: {
        cormorant: ["Cormorant-Bold"],
        sans: ["Poppins"],
      },
      fontSize: {
        PACT: [
          "35px",
          {
            letterSpacing: "0.2em",
          },
        ],
      },
      colors: {
        "rouge-logo": "#EA4335",
        primary: "#F2771B",
        secondary: "#0a0035",
        base100: "#F1F3F4",
        base200: "#E0E0E0",
        base300: "#CCCCCC",
        neutre: "#000",
        gris: "#828282",
        blur: "#F1F3F4",
      },
      spacing: {
        "1/6": "16%",
      },
      animation: {
        "expand-width": "expandWidth 1s ease-out forwards",
        scale: "scale 1s ease-in-out infinite",
      },
      keyframes: {
        expandWidth: {
          "0%": { width: "100%" },
          "100%": { width: "0%" },
        },
        scale: {
          "0%, 100%": { transform: "scale(1.01)" },
          "50%": { transform: "scale(0.99)" },
        },
      },
      boxShadow: {
        custom: "0 0 12px 12px rgba(210, 210, 210, 0.5)",
      },
    },
  },
  plugins: [],
};
