import React from 'react'
import ReactDOM from 'react-dom/client'
import App from './App.tsx'
import './index.css'
import { BrowserRouter } from 'react-router-dom'
import { FooterPage } from './pages/layouts/Footer.tsx'
import { HeaderPage } from './pages/layouts/Header.tsx'

ReactDOM.createRoot(document.getElementById('root')!).render(
  <React.StrictMode>
    <HeaderPage />
    <BrowserRouter>
      <App />
    </BrowserRouter>
    <FooterPage />
  </React.StrictMode>,
)
