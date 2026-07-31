import tailwindcss from '@tailwindcss/vite';
import react from '@vitejs/plugin-react';
import path from 'path';
import { defineConfig, loadEnv } from 'vite';

export default defineConfig(({ mode }) => {
  const env = loadEnv(mode, '.', '');
  const BACKEND_URL = env.VITE_BACKEND_URL || 'http://localhost:8000';

  return {
    plugins: [react(), tailwindcss()],

    define: {
      'process.env.GEMINI_API_KEY': JSON.stringify(env.GEMINI_API_KEY),
    },

    resolve: {
      alias: {
        // '@' points to src/ for clean imports: import X from '@/components/...'
        '@': path.resolve(__dirname, './src'),
      },
    },

    server: {
      port: 3000,
      host: '0.0.0.0',
      // HMR is disabled in AI Studio via DISABLE_HMR env var.
      hmr: process.env.DISABLE_HMR !== 'true',

      // Proxy all /api/* and /sanctum/* calls to Laravel backend
      // This ensures session cookies and CSRF work correctly (same-origin).
      proxy: {
        '/api': {
          target: BACKEND_URL,
          changeOrigin: true,
          secure: false,
        },
        '/sanctum': {
          target: BACKEND_URL,
          changeOrigin: true,
          secure: false,
        },
        '/login': {
          target: BACKEND_URL,
          changeOrigin: true,
          secure: false,
        },
        '/logout': {
          target: BACKEND_URL,
          changeOrigin: true,
          secure: false,
        },
      },
    },
  };
});
