import { useEffect, useState } from 'react'
import { PokemonInterface } from '../../shared/interfaces/Pokemon.interface'
import { PokemonImageComponent } from '../../components/PokemonImage'
import { grid } from 'ldrs'

export const HeaderPage = () => {
    const [flagBar, setAlterBar] = useState(false)
    const [flagSearch, setAlterSearch] = useState(false)
    const [flagLoadingSearch, setAlterLoadingSearch] = useState(false)

    const [stringSearch, setStringSearch] = useState("")
    const [pokemonsSearch, setPokemonsSearch] = useState(Array<PokemonInterface>)
    
    const alterBar = () => {
        setAlterBar(!flagBar)
    }
    
    const findPokemon = async (name: string) => {
        name = name.toLowerCase().replaceAll(" ", "-")
        const response = await fetch(`${import.meta.env.VITE_POKEAPI_LOCAL_URI}/api/pokemon/${name}`)
        const data = await response.json()

        setPokemonsSearch(data.code === 200 ? data.data : [])
        setAlterLoadingSearch(false)
    }

    const searchPokemon = (e:any) => {
        setStringSearch(e.target.value.trim())
    }

    useEffect(() => {
        if(stringSearch.length > 3) {
            setAlterLoadingSearch(true)
            setAlterSearch(true)
            findPokemon(stringSearch)
        } else {
            setAlterSearch(false)
        }
    }, [stringSearch])

    grid.register()

    return (<header className="bg-gray-100 px-4 py-2 drop-shadow-md fixed w-full z-30">
        <div className="flex justify-between">
            <div className="w-fit">
                <a href="/pokemon"><h1 className="text-3xl font-black">Pokefirma</h1></a>
            </div>
            <div className="flex space-x-4">
                <div className="hidden md:block self-center">
                    <div>
                        <input onChange={searchPokemon} className="rounded-lg bg-white w-64 p-2 focus:outline-gray-300 text-gray-400" type="text" placeholder="Buscar pokemon por nombre" />
                    </div>
                    <div className={`relative ${flagSearch ? "" : "hidden"}`}>
                        <div className="absolute bg-gray-100 w-full border border-solid border-gray-200">
                            {
                                flagLoadingSearch ? <div className='text-center grid justify-items-center space-y-2 py-2'>
                                    <l-grid
                                        size="60"
                                        speed="1.5" 
                                        color="gray" 
                                    ></l-grid>
                                    <span className='text-gray-400'>Buscando pokemones...</span>
                                </div> : 
                                <div className='overflow-y-auto max-h-[500px]'>
                                    <ul className='[&>li]:p-2 [&>li:hover]:bg-gray-200'>
                                        {pokemonsSearch.map((pokemon: PokemonInterface) => <li key={`pokemonsearch_${pokemon.pokemonId}`} className='group flex justify-between items-center'>
                                            <a href={`/pokemon/${pokemon.pokemonId}`} className='flex space-x-2 w-full items-center'>
                                                <PokemonImageComponent 
                                                    src={pokemon.image} 
                                                    alt={`Pokemon ${pokemon.name}`} 
                                                    width={35} 
                                                    height={35} 
                                                    classNames={`rounded-full bg-gray-300 group-hover:scale-[120%] duration-300`}/>
                                                    
                                                <div>
                                                    <span className='text-gray-500 capitalize'>{pokemon.name.replaceAll("-", " ")}</span>
                                                    <div className='text-sm space-x-1 pt-0.5'>
                                                        {
                                                            pokemon.types.map((type, index) => <span key={`search_${pokemon.pokemonId}_${index}`} className={`${type.name} rounded-lg px-1 py-0.5 text-white`}>{type.name}</span>)
                                                        }
                                                    </div>
                                                </div>
                                            </a>
                                            <span className='text-gray-400'>#{pokemon.pokemonId}</span>
                                        </li>)}
                                    </ul>
                                </div>
                            }
                        </div>
                    </div>
                </div>
                <div className="self-center hover:bg-gray-200 p-1 rounded-lg border border-dashed border-gray-300 cursor-pointer">
                    <div onClick={alterBar}>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="size-6">
                            <path strokeLinecap="round" strokeLinejoin="round" d="M3.75 5.25h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5" />
                        </svg>
                    </div>
                    <div className={`${flagBar ? "w-[100%] flex justify-center" : ""} bg-gray-100 w-[0] h-max min-h-screen z-20 fixed right-0 mt-4 duration-300 cursor-default`}>
                        <div>
                            <img 
                                src="https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/f/c734f9a3-d86f-4377-b639-87459c3202a0/d6o72vo-06187a6b-3483-4335-ac1a-64ba27816fbc.png?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1cm46YXBwOjdlMGQxODg5ODIyNjQzNzNhNWYwZDQxNWVhMGQyNmUwIiwiaXNzIjoidXJuOmFwcDo3ZTBkMTg4OTgyMjY0MzczYTVmMGQ0MTVlYTBkMjZlMCIsIm9iaiI6W1t7InBhdGgiOiJcL2ZcL2M3MzRmOWEzLWQ4NmYtNDM3Ny1iNjM5LTg3NDU5YzMyMDJhMFwvZDZvNzJ2by0wNjE4N2E2Yi0zNDgzLTQzMzUtYWMxYS02NGJhMjc4MTZmYmMucG5nIn1dXSwiYXVkIjpbInVybjpzZXJ2aWNlOmZpbGUuZG93bmxvYWQiXX0.5klIWcbAkvRse4cS8tpk1WBPzg1e92TsmCx46yOJw2Q" 
                                alt="user"
                                width={150}
                                height={150}
                                className='rounded-full' />
                            <div className="text-center">
                                <h2 className="text-2xl">Ash Firma</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>)
}