import { useEffect, useState } from 'react';
import { useParams } from 'react-router';
import { PokemonInterface } from '../shared/interfaces/Pokemon.interface';
import { PokemonChartStatsComponent } from '../components/PokemonChartStats';
import { PokemonLeftInfoComponent } from '../components/PokemonLeftInfo';

export const PokemonPage = () => {
    const [pokemon, setPokemon] = useState<PokemonInterface>({});
    const params = useParams()

    const getPokemon = async () => {
        const response = await fetch(`${import.meta.env.VITE_POKEAPI_LOCAL_URI}/api/pokemon/${params.id}`);
        const data = await response.json();
        setPokemon(data.code === 200 ? data.data : null);
    }

    useEffect(() => {
        getPokemon();
    }, [])
    
    console.log(pokemon)
    return (<div className='min-h-screen grid grid-cols-1 xl:grid-cols-2 px-4 pt-20 gap-x-4 gap-y-2 py-2'>
        <div className='hidden lg:block'><PokemonLeftInfoComponent pokemon={pokemon}/></div>
        <div className='grid grid-rows-2 border-x border-solid border-gray-200'>
            <div className='grid place-content-center border-b border-solid boder-gray-100'>
                <img 
                    src={pokemon.image} 
                    alt={`Pokemon ${pokemon.name}`}
                    className="bg-gray-100 pokemon-img hover:bg-gray-200 duration-300 hover:scale-[105%] rounded-full w-auto h-auto max-w-[350px] max-h-[350px]" />
            </div>
            <div>
                <PokemonChartStatsComponent pokemon={pokemon} />
            </div>
        </div>
        <div className='block lg:hidden'><PokemonLeftInfoComponent pokemon={pokemon}/></div>
    </div>)
}