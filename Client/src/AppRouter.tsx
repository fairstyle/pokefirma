import { Route, Routes, Navigate } from "react-router-dom"
import { PokemonPage } from "./pages/Pokemon"
import { PokemonHomePage } from "./pages/PokemonHome"

export const AppRouter = () => {
  return <Routes>
        <Route path="/" element={<PokemonHomePage />} />
        <Route path="/pokemon" element={<PokemonHomePage />} />
        <Route path="/pokemon/:id" element={<PokemonPage />} />

        <Route path="*" element={<Navigate to='/' />} />
    </Routes> 
}