import { useEffect, useState } from 'react';
import { useParams } from 'react-router';
import { PokemonInterface } from '../shared/interfaces/Pokemon.interface';

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

    return (<div className='h-auto min-h-screen grid grid-cols-3 px-4 pt-20'>
        <div className=''>1</div>
        <div className='grid grid-rows-2'>
            <div>
                <img 
                    src={pokemon.image} 
                    alt={`Pokemon ${pokemon.name}`}
                    className="bg-gray-100 pokemon-img hover:bg-gray-200 duration-300 hover:scale-[105%] rounded-full" />
            </div>
            <div>2.2</div>
        </div>
        <div className=''>3</div>
    </div>)
}