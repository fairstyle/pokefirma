import { useEffect, useState, useRef} from 'react'
import { PokemonCardComponent } from '../components/PokemonCard'
import { PokemonInterface } from '../shared/interfaces/Pokemon.interface'
import { PokemonTypesInterface } from '../shared/interfaces/PokemonTypes.interface'
import { grid } from 'ldrs'

export const PokemonHomePage = () => {

    // States
    const [pokemons, setPokemons] = useState(Array<PokemonInterface>)
    const [pokemonsTypes, setPokemonsTypes] = useState(Array<PokemonTypesInterface>)
    const [typeFiltros, setTypeFiltros] = useState(Array<number>)
    const [orderBy, setOrderBy] = useState(Array<string>)
    const [opts, setOpts] = useState(Array<number>)
    const [pokemonCount, setPokemonCount] = useState(0)

    // Flags
    const [flagApplyFiltros, setFlagApplyFiltros] = useState(false)
    const [flagApplyOrder, setFlagApplyOrder] = useState(false)
    const [isOpenedByOrder, setByOrder] = useState(false)
    const [isOpenedByType, setByType] = useState(false)
    const [isLoading, setLoading] = useState(false)

    const initialized = useRef(false)

    const getPokemons = async (opts: Array<number> = [20 , 0]) => {
        const response = await fetch(`${import.meta.env.VITE_POKEAPI_LOCAL_URI}/api/pokemon?limit=${opts[0]}&offset=${opts[1]}${flagApplyFiltros ? "&types=["+typeFiltros.join(',')+"]" : ""}${flagApplyOrder ? "&orderby=["+orderBy.join(',')+"]" : ""}`)
        const data = await response.json()

        // 0 = Limit
        // 1 = Offset
        setOpts([opts[0], opts[0] + opts[1]])
        setPokemons((prevPokemons) => [...prevPokemons, ...(data.code === 200 ? data.data : [])])
        setLoading(false)
    }

    const getPokemonTypes = async () => {
        const response = await fetch(`${import.meta.env.VITE_POKEAPI_LOCAL_URI}/api/types`)
        const data = await response.json()

        setPokemonsTypes(data.code === 200 ? data.data : [])
    }

    const getPokemonsCount = async () => {
        const response = await fetch(`${import.meta.env.VITE_POKEAPI_LOCAL_URI}/api/pokemon_count`)
        const data = await response.json()

        setPokemonCount(data.code === 200 ? data.data : 0)
    }
    
    const applyFiltros = async () => {
        setPokemons([])
        setByType(false)
        setByOrder(false)
        setLoading(true)
        getPokemons()
    }

    const loadMorePokemons = () => {
        setLoading(true)
        getPokemons([opts[0], opts[1]])
    }

    const alterByType = () => {
        setByOrder(false)
        setByType(!isOpenedByType)
    }

    const alterByOrder = () => {
        setByType(false)
        setByOrder(!isOpenedByOrder)
    }

    const handleTypeChange = (e: any) => {
        if(e.target.checked)
            setTypeFiltros([...typeFiltros, e.target.value])
        else
            setTypeFiltros(typeFiltros.filter((type: any) => type !== e.target.value))
    }

    const changeOrder = (e: any) => {

        while(e.target.dataset.ordername === undefined)
            e.target = e.target.parentElement

        let exists:any = orderBy.findIndex((order) => order.includes(e.target.dataset.ordername))
        if(exists !== -1) {
            let status = orderBy[exists].split(":")[1]
            if(status === "-1")
                orderBy.splice(exists, 1)
            else
                orderBy[exists] = e.target.dataset.ordername+":"+(status === "1" ? "-1" : "1")
        } else
            orderBy.push(e.target.dataset.ordername+":1")

        setOrderBy([...orderBy])
    }

    const getOrderIndex = (id: string, nameOrder: string)  => {
        const indx = orderBy.findIndex((order) => order.includes(nameOrder))
        if(indx === -1 && id === "0") 
            return true
        
        if(indx !== -1)
            return orderBy[indx].split(":")[1] === id

        return false
    }

    const disableFlags = () => {
        setByType(false)
        setByOrder(false)
    }

    useEffect(() => {
        setFlagApplyFiltros(typeFiltros.length > 0)
    }, [typeFiltros])

    useEffect(() => {
        setFlagApplyOrder(orderBy.length > 0)
    }, [orderBy])

    useEffect(() => {
        if (!initialized.current) {
            initialized.current = true
            getPokemons()
            getPokemonTypes()
            getPokemonsCount()
        }
    }, [])

    grid.register()
    
    return (<>
        <div className='pt-20 flex justify-end px-4 pb-4 space-x-2'>
            <div className='w-[300px] bg-gray-100 border border-solid border-gray-200 rounded-lg'>
                <div className='flex justify-between p-2'>
                    <div className='text-gray-400'>{flagApplyOrder ? "Custom" : "Default" }</div>
                    <div className='cursor-pointer' onClick={alterByOrder}>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="size-6">
                            <path strokeLinecap="round" strokeLinejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </div>
                </div>
                <div className='relative z-20'>
                    <div className={`${isOpenedByOrder ? "h-[300px]" : "hidden"} absolute bg-gray-200 border-gray-300 border border-solid right-0 w-full duration-300 p-4 space-y-2 [&>div]:p-2 [&>div]:flex [&>div]:justify-between [&>div]:rounded-lg [&>div:hover]:bg-gray-300 [&>div:hover]:cursor-pointer overflow-y-auto`}>
                        <div onClick={changeOrder} data-ordername="hp">
                            <span>HP</span>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className={`${getOrderIndex("0", "hp") ? "" : "hidden"} size-6`}>
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M3 7.5 7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5" />
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className={`${getOrderIndex("-1", "hp") ? "" : "hidden"} size-6`}>
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M3 4.5h14.25M3 9h9.75M3 13.5h9.75m4.5-4.5v12m0 0-3.75-3.75M17.25 21 21 17.25" />
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className={`${getOrderIndex("1", "hp") ? "" : "hidden"} size-6`}>
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M3 4.5h14.25M3 9h9.75M3 13.5h5.25m5.25-.75L17.25 9m0 0L21 12.75M17.25 9v12" />
                                </svg>
                            </div>
                        </div>
                        <div onClick={changeOrder} data-ordername="attack">
                            <span>Attack</span>
                            <div>
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className={`${getOrderIndex("0", "attack") ? "" : "hidden"} size-6`}>
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M3 7.5 7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className={`${getOrderIndex("-1", "attack") ? "" : "hidden"} size-6`}>
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M3 4.5h14.25M3 9h9.75M3 13.5h9.75m4.5-4.5v12m0 0-3.75-3.75M17.25 21 21 17.25" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className={`${getOrderIndex("1", "attack") ? "" : "hidden"} size-6`}>
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M3 4.5h14.25M3 9h9.75M3 13.5h5.25m5.25-.75L17.25 9m0 0L21 12.75M17.25 9v12" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div onClick={changeOrder} data-ordername="defense">
                            <span>Defense</span>
                            <div>
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className={`${getOrderIndex("0", "defense") ? "" : "hidden"} size-6`}>
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M3 7.5 7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className={`${getOrderIndex("-1", "defense") ? "" : "hidden"} size-6`}>
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M3 4.5h14.25M3 9h9.75M3 13.5h9.75m4.5-4.5v12m0 0-3.75-3.75M17.25 21 21 17.25" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className={`${getOrderIndex("1", "defense") ? "" : "hidden"} size-6`}>
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M3 4.5h14.25M3 9h9.75M3 13.5h5.25m5.25-.75L17.25 9m0 0L21 12.75M17.25 9v12" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div onClick={changeOrder} data-ordername="speed">
                            <span>Speed</span>
                            <div>
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className={`${getOrderIndex("0", "speed") ? "" : "hidden"} size-6`}>
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M3 7.5 7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className={`${getOrderIndex("-1", "speed") ? "" : "hidden"} size-6`}>
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M3 4.5h14.25M3 9h9.75M3 13.5h9.75m4.5-4.5v12m0 0-3.75-3.75M17.25 21 21 17.25" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className={`${getOrderIndex("1", "speed") ? "" : "hidden"} size-6`}>
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M3 4.5h14.25M3 9h9.75M3 13.5h5.25m5.25-.75L17.25 9m0 0L21 12.75M17.25 9v12" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div onClick={changeOrder} data-ordername="special_attack">
                            <span>Special Attack</span>
                            <div>
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className={`${getOrderIndex("0", "special_attack") ? "" : "hidden"} size-6`}>
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M3 7.5 7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className={`${getOrderIndex("-1", "special_attack") ? "" : "hidden"} size-6`}>
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M3 4.5h14.25M3 9h9.75M3 13.5h9.75m4.5-4.5v12m0 0-3.75-3.75M17.25 21 21 17.25" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className={`${getOrderIndex("1", "special_attack") ? "" : "hidden"} size-6`}>
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M3 4.5h14.25M3 9h9.75M3 13.5h5.25m5.25-.75L17.25 9m0 0L21 12.75M17.25 9v12" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div onClick={changeOrder} data-ordername="special_defense">
                            <span>Special Defense</span>
                            <div>
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className={`${getOrderIndex("0", "special_defense") ? "" : "hidden"} size-6`}>
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M3 7.5 7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className={`${getOrderIndex("-1", "special_defense") ? "" : "hidden"} size-6`}>
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M3 4.5h14.25M3 9h9.75M3 13.5h9.75m4.5-4.5v12m0 0-3.75-3.75M17.25 21 21 17.25" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className={`${getOrderIndex("1", "special_defense") ? "" : "hidden"} size-6`}>
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M3 4.5h14.25M3 9h9.75M3 13.5h5.25m5.25-.75L17.25 9m0 0L21 12.75M17.25 9v12" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div className='w-[300px] bg-gray-100 border border-solid border-gray-200 rounded-lg'>
                <div className='flex justify-between p-2'>
                    <div className='text-gray-400'>{flagApplyFiltros ? "Custom" : "Default" }</div>
                    <div className='cursor-pointer' onClick={alterByType}>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="size-6">
                            <path strokeLinecap="round" strokeLinejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </div>
                </div>
                <div className='relative z-20 duration-300'>
                    <div className={`${isOpenedByType ? "h-[300px]" : "hidden"} absolute bg-gray-200 border-gray-300 border border-solid right-0 w-full duration-300 p-4 space-y-6 overflow-y-auto`}>
                        {pokemonsTypes.map((pokemonType:any) => 
                            <div key={`pokemontype_${pokemonType.pokemonTypeId}`} className='space-x-2'>
                                <input onChange={handleTypeChange} type='checkbox' name='pokemonType' value={pokemonType.pokemonTypeId} className="bg-red-300" />
                                <label className={`${pokemonType.name} p-2 rounded-lg text-white`}>{pokemonType.name}</label>
                            </div>
                        )}
                    </div>
                </div>
            </div>
            <div>
                <button onClick={applyFiltros} className='cursor-pointer p-2 rounded-lg bg-blue-300 hover:bg-blue-400 text-white hover:scale-[110%] active:scale-[95%] font-bold duration-300'>Aplicar Filtros</button>
            </div>
        </div>
        {
            pokemons.length > 0  ? (
                <div onClick={disableFlags} className='h-auto min-h-screen px-4 pb-4 space-y-4'>
                    <div className='grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-2'>
                        {pokemons.map((pokemon:any) => <PokemonCardComponent pokemon={pokemon} key={`pokemon_${pokemon.pokemonId}`}></PokemonCardComponent>)}
                    </div>
                    <div className='text-md text-center text-gray-300 w-full'>----------------------- Total Pokemones cargados en la vista: {pokemons.length} de {pokemonCount} -----------------------</div>
                    <div className='grid justify-items-center space-y-4 text-center'>
                        <div className={`${isLoading ? "" : "hidden"}`}>
                            <div>
                                <l-grid
                                    size="60"
                                    speed="1.5" 
                                    color="gray" 
                                ></l-grid>
                            </div>
                            <span className='font-md text-gray-400'>Cargando más pokemones...... recuerda que las cargas iniciales demoran un poco</span>
                        </div>
                        <button onClick={loadMorePokemons} className='cursor-pointer p-2 rounded-lg bg-blue-300 hover:bg-blue-400 text-white hover:scale-[110%] active:scale-[95%] font-bold duration-300'>Cargar más</button>
                    </div>
                </div>) : (
                <div className='w-full text-center px-4 pt-20 pb-4'>
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
    </>)
}