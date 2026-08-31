/**
 * ============================================================================
 * main.jsx — Application Entry Point
 * ============================================================================
 * Unchanged structure. Renders the root App component into #root.
 * ============================================================================
 */

import { StrictMode } from 'react'
import { createRoot } from 'react-dom/client'
import { Analytics } from "@vercel/analytics/react";
import './index.css'
import App from './App.jsx'

createRoot(document.getElementById('root')).render(
  <StrictMode>
    <App />
     <Analytics />
  </StrictMode>,
)
