import { useEffect, useState } from 'react'
import { PokemonCardComponent } from '../components/PokemonCard'
import { PokemonInterface } from '../shared/interfaces/Pokemon.interface'
import { grid } from 'ldrs'

export const PokemonHomePage = () => {
    const [pokemons, setPokemons] = useState(Array<PokemonInterface>)
    const [opts, setOpts] = useState(Array<number>)
    const [isLoading, setLoading] = useState(false)

    const getPokemons = async (opts: Array<number> = [20 , 0]) => {
        const response = await fetch(`${import.meta.env.VITE_POKEAPI_LOCAL_URI}/api/pokemon?limit=${opts[0]}&offset=${opts[1]}`)
        const data = await response.json()

        // 0 = Limit
        // 1 = Offset
        setOpts([opts[0], opts[0] + opts[1]])
        setPokemons(data.code === 200 ? [...pokemons, ...data.data] : [...pokemons, ...[]])
        setLoading(false)
    }

    const loadMorePokemons = () => {
        setLoading(true)
        getPokemons([opts[0], opts[1]])
    }

    useEffect(() => {
        getPokemons()
    }, [])
    

    grid.register()
    
    return pokemons.length > 0  ? (<>
        <div className='h-auto min-h-screen px-4 pt-2 pb-4 space-y-4'>
            <div className='grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-2'>
                {pokemons.map((pokemon:PokemonInterface) => <PokemonCardComponent pokemon={pokemon} key={pokemon.pokemonId}></PokemonCardComponent>)}
            </div>
            <div className='grid justify-items-center space-y-4 text-center'>
                <div className={`${isLoading ? "" : "hidden"}`}>
                    <div>
                        <l-grid
                            size="60"
                            speed="1.5" 
                            color="gray" 
                        ></l-grid>
                    </div>
                    <span className='font-md text-gray-400'>Cargando más pokemones......</span>
                </div>
                <button onClick={loadMorePokemons} className='cursor-pointer p-2 rounded-lg bg-blue-300 hover:bg-blue-400 text-white hover:scale-[110%] active:scale-[95%] font-bold duration-300'>Cargar más</button>
            </div>
        </div>
    </>) : (
        <div className='w-full text-center px-4 pt-2 pb-4'>
            <div>
                <l-grid
                    size="60"
                    speed="1.5" 
                    color="gray" 
                ></l-grid>
            </div>
            <span>Cargando más pokemones</span>
        </div>)
}