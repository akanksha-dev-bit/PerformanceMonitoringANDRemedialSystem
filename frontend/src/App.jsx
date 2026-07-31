/**
 * ============================================================================
 * App.jsx — Root Component
 * ============================================================================
 * Establishes the application shell:
 *   - React Router BrowserRouter
 *   - AuthProvider Context
 *   - AppRouter Switch
 * ============================================================================
 */

import { BrowserRouter } from 'react-router-dom';
import { AuthProvider }  from './context/AuthContext';
import AppRouter         from './router';

export default function App() {
  return (
    <BrowserRouter>
      <AuthProvider>
        <AppRouter />
      </AuthProvider>
    </BrowserRouter>
  );
}
