/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./app/View/Components/**/*.php",
  ],
  darkMode: 'class', // Modo escuro por classe
  theme: {
    extend: {
      // Paleta de cores corporativa HCP
      colors: {
        // Cores primárias da HCP
        'hcp': {
          50: '#f0f9ff',
          100: '#e0f2fe',
          200: '#bae6fd',
          300: '#7dd3fc',
          400: '#38bdf8',
          500: '#0ea5e9', // Cor principal HCP
          600: '#0284c7',
          700: '#0369a1',
          800: '#075985',
          900: '#0c4a6e',
          950: '#082f49',
        },
        // Cores secundárias
        'hcp-secondary': {
          50: '#f8fafc',
          100: '#f1f5f9',
          200: '#e2e8f0',
          300: '#cbd5e1',
          400: '#94a3b8',
          500: '#64748b',
          600: '#475569',
          700: '#334155',
          800: '#1e293b',
          900: '#0f172a',
          950: '#020617',
        },
        // Cores de acento
        'hcp-accent': {
          50: '#fefce8',
          100: '#fef9c3',
          200: '#fef08a',
          300: '#fde047',
          400: '#facc15',
          500: '#eab308',
          600: '#ca8a04',
          700: '#a16207',
          800: '#854d0e',
          900: '#713f12',
          950: '#422006',
        },
        // Cores de sucesso
        'hcp-success': {
          50: '#f0fdf4',
          100: '#dcfce7',
          200: '#bbf7d0',
          300: '#86efac',
          400: '#4ade80',
          500: '#22c55e',
          600: '#16a34a',
          700: '#15803d',
          800: '#166534',
          900: '#14532d',
          950: '#052e16',
        },
        // Cores de erro
        'hcp-error': {
          50: '#fef2f2',
          100: '#fee2e2',
          200: '#fecaca',
          300: '#fca5a5',
          400: '#f87171',
          500: '#ef4444',
          600: '#dc2626',
          700: '#b91c1c',
          800: '#991b1b',
          900: '#7f1d1d',
          950: '#450a0a',
        },
        // Cores de aviso
        'hcp-warning': {
          50: '#fffbeb',
          100: '#fef3c7',
          200: '#fde68a',
          300: '#fcd34d',
          400: '#fbbf24',
          500: '#f59e0b',
          600: '#d97706',
          700: '#b45309',
          800: '#92400e',
          900: '#78350f',
          950: '#451a03',
        },
      },
      // Tipografia corporativa
      fontFamily: {
        'hcp-sans': ['Inter', 'system-ui', 'sans-serif'],
        'hcp-serif': ['Merriweather', 'serif'],
        'hcp-mono': ['JetBrains Mono', 'monospace'],
      },
      // Tamanhos de fonte personalizados
      fontSize: {
        'hcp-xs': ['0.75rem', { lineHeight: '1rem' }],
        'hcp-sm': ['0.875rem', { lineHeight: '1.25rem' }],
        'hcp-base': ['1rem', { lineHeight: '1.5rem' }],
        'hcp-lg': ['1.125rem', { lineHeight: '1.75rem' }],
        'hcp-xl': ['1.25rem', { lineHeight: '1.75rem' }],
        'hcp-2xl': ['1.5rem', { lineHeight: '2rem' }],
        'hcp-3xl': ['1.875rem', { lineHeight: '2.25rem' }],
        'hcp-4xl': ['2.25rem', { lineHeight: '2.5rem' }],
        'hcp-5xl': ['3rem', { lineHeight: '1' }],
        'hcp-6xl': ['3.75rem', { lineHeight: '1' }],
      },
      // Espaçamentos personalizados
      spacing: {
        '18': '4.5rem',
        '88': '22rem',
        '128': '32rem',
      },
      // Bordas arredondadas
      borderRadius: {
        'hcp-sm': '0.25rem',
        'hcp': '0.5rem',
        'hcp-md': '0.75rem',
        'hcp-lg': '1rem',
        'hcp-xl': '1.5rem',
        'hcp-2xl': '2rem',
        'hcp-3xl': '3rem',
      },
      // Sombras personalizadas
      boxShadow: {
        'hcp-sm': '0 1px 2px 0 rgb(0 0 0 / 0.05)',
        'hcp': '0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1)',
        'hcp-md': '0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1)',
        'hcp-lg': '0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1)',
        'hcp-xl': '0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1)',
        'hcp-2xl': '0 25px 50px -12px rgb(0 0 0 / 0.25)',
        'hcp-inner': 'inset 0 2px 4px 0 rgb(0 0 0 / 0.05)',
        'hcp-glass': '0 25px 50px -12px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(255, 255, 255, 0.2)',
      },
      // Animações personalizadas
      animation: {
        'fade-in': 'fadeIn 0.5s ease-in-out',
        'fade-out': 'fadeOut 0.5s ease-in-out',
        'slide-in-up': 'slideInUp 0.3s ease-out',
        'slide-in-down': 'slideInDown 0.3s ease-out',
        'slide-in-left': 'slideInLeft 0.3s ease-out',
        'slide-in-right': 'slideInRight 0.3s ease-out',
        'scale-in': 'scaleIn 0.2s ease-out',
        'scale-out': 'scaleOut 0.2s ease-in',
        'bounce-gentle': 'bounceGentle 0.6s ease-out',
        'pulse-gentle': 'pulseGentle 2s ease-in-out infinite',
        'float': 'float 3s ease-in-out infinite',
        'glow': 'glow 2s ease-in-out infinite alternate',
        'shimmer': 'shimmer 2s linear infinite',
        'confetti': 'confetti 0.5s ease-out',
      },
      // Keyframes das animações
      keyframes: {
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
        fadeOut: {
          '0%': { opacity: '1' },
          '100%': { opacity: '0' },
        },
        slideInUp: {
          '0%': { transform: 'translateY(100%)', opacity: '0' },
          '100%': { transform: 'translateY(0)', opacity: '1' },
        },
        slideInDown: {
          '0%': { transform: 'translateY(-100%)', opacity: '0' },
          '100%': { transform: 'translateY(0)', opacity: '1' },
        },
        slideInLeft: {
          '0%': { transform: 'translateX(-100%)', opacity: '0' },
          '100%': { transform: 'translateX(0)', opacity: '1' },
        },
        slideInRight: {
          '0%': { transform: 'translateX(100%)', opacity: '0' },
          '100%': { transform: 'translateX(0)', opacity: '1' },
        },
        scaleIn: {
          '0%': { transform: 'scale(0.9)', opacity: '0' },
          '100%': { transform: 'scale(1)', opacity: '1' },
        },
        scaleOut: {
          '0%': { transform: 'scale(1)', opacity: '1' },
          '100%': { transform: 'scale(0.9)', opacity: '0' },
        },
        bounceGentle: {
          '0%, 20%, 53%, 80%, 100%': { transform: 'translate3d(0,0,0)' },
          '40%, 43%': { transform: 'translate3d(0, -15px, 0)' },
          '70%': { transform: 'translate3d(0, -7px, 0)' },
          '90%': { transform: 'translate3d(0, -2px, 0)' },
        },
        pulseGentle: {
          '0%, 100%': { opacity: '1' },
          '50%': { opacity: '0.7' },
        },
        float: {
          '0%, 100%': { transform: 'translateY(0px)' },
          '50%': { transform: 'translateY(-10px)' },
        },
        glow: {
          '0%': { boxShadow: '0 0 5px rgba(14, 165, 233, 0.5)' },
          '100%': { boxShadow: '0 0 20px rgba(14, 165, 233, 0.8)' },
        },
        shimmer: {
          '0%': { backgroundPosition: '-200% 0' },
          '100%': { backgroundPosition: '200% 0' },
        },
        confetti: {
          '0%': { transform: 'scale(0) rotate(0deg)', opacity: '1' },
          '100%': { transform: 'scale(1) rotate(180deg)', opacity: '0' },
        },
      },
      // Transições personalizadas
      transitionDuration: {
        '400': '400ms',
        '600': '600ms',
        '800': '800ms',
        '900': '900ms',
      },
      // Backdrop blur personalizado
      backdropBlur: {
        'hcp': '20px',
        'hcp-sm': '10px',
        'hcp-lg': '30px',
      },
      // Gradientes personalizados
      backgroundImage: {
        'hcp-gradient': 'linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%)',
        'hcp-gradient-dark': 'linear-gradient(135deg, #075985 0%, #0c4a6e 100%)',
        'hcp-success-gradient': 'linear-gradient(135deg, #22c55e 0%, #16a34a 100%)',
        'hcp-accent-gradient': 'linear-gradient(135deg, #eab308 0%, #ca8a04 100%)',
        'hcp-glass': 'linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%)',
        'hcp-glass-dark': 'linear-gradient(135deg, rgba(30, 41, 59, 0.8) 0%, rgba(15, 23, 42, 0.9) 100%)',
        'shimmer-gradient': 'linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent)',
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms')({
      strategy: 'class',
    }),
    // Plugin personalizado para utilitários HCP
    function({ addUtilities, addComponents, theme }) {
      // Utilitários para glassmorphism
      addUtilities({
        '.glass': {
          background: 'rgba(255, 255, 255, 0.1)',
          backdropFilter: 'blur(20px)',
          border: '1px solid rgba(255, 255, 255, 0.2)',
        },
        '.glass-dark': {
          background: 'rgba(30, 41, 59, 0.8)',
          backdropFilter: 'blur(20px)',
          border: '1px solid rgba(148, 163, 184, 0.2)',
        },
        '.shimmer': {
          background: 'linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent)',
          backgroundSize: '200% 100%',
          animation: 'shimmer 2s linear infinite',
        },
      });

      // Componentes base HCP
      addComponents({
        '.btn-hcp': {
          padding: `${theme('spacing.3')} ${theme('spacing.6')}`,
          borderRadius: theme('borderRadius.hcp'),
          fontWeight: theme('fontWeight.medium'),
          fontSize: theme('fontSize.hcp-sm'),
          transition: 'all 0.2s ease-in-out',
          cursor: 'pointer',
          display: 'inline-flex',
          alignItems: 'center',
          justifyContent: 'center',
          gap: theme('spacing.2'),
          '&:focus': {
            outline: 'none',
            boxShadow: `0 0 0 3px ${theme('colors.hcp.500')}40`,
          },
        },
        '.btn-hcp-primary': {
          backgroundColor: theme('colors.hcp.500'),
          color: theme('colors.white'),
          '&:hover': {
            backgroundColor: theme('colors.hcp.600'),
            transform: 'translateY(-1px)',
            boxShadow: theme('boxShadow.hcp-md'),
          },
          '&:active': {
            transform: 'translateY(0)',
          },
        },
        '.btn-hcp-secondary': {
          backgroundColor: theme('colors.hcp-secondary.100'),
          color: theme('colors.hcp-secondary.700'),
          '&:hover': {
            backgroundColor: theme('colors.hcp-secondary.200'),
            transform: 'translateY(-1px)',
            boxShadow: theme('boxShadow.hcp-md'),
          },
        },
        '.card-hcp': {
          backgroundColor: theme('colors.white'),
          borderRadius: theme('borderRadius.hcp-xl'),
          boxShadow: theme('boxShadow.hcp'),
          padding: theme('spacing.6'),
          transition: 'all 0.3s ease-in-out',
          '&:hover': {
            boxShadow: theme('boxShadow.hcp-lg'),
            transform: 'translateY(-2px)',
          },
        },
        '.card-hcp-dark': {
          backgroundColor: theme('colors.hcp-secondary.800'),
          color: theme('colors.white'),
        },
        '.input-hcp': {
          borderRadius: theme('borderRadius.hcp'),
          borderColor: theme('colors.hcp-secondary.300'),
          padding: `${theme('spacing.3')} ${theme('spacing.4')}`,
          fontSize: theme('fontSize.hcp-base'),
          transition: 'all 0.2s ease-in-out',
          '&:focus': {
            outline: 'none',
            borderColor: theme('colors.hcp.500'),
            boxShadow: `0 0 0 3px ${theme('colors.hcp.500')}20`,
          },
        },
      });
    },
  ],
}