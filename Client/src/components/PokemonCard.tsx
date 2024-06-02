import { PokemonInterface } from "../shared/interfaces/Pokemon.interface"
import { AnimatedLink } from "./AnimatedLink"

export const PokemonCardComponent = (pokemon: any) => {
    const unipokemon: PokemonInterface = pokemon.pokemon

    return (<AnimatedLink to={`/pokemon/${unipokemon.pokemonId}`}>
        <div className="p-2 grid justify-items-center shadow-md rounded-lg relative hover:bg-gray-50 duration-300 group">
            <span className="text-xl text-gray-300 absolute left-2 top-2">{unipokemon.pokemonId}°</span>
            <div className="grid grid-cols-2 w-full py-2 pl-4">
                <div>
                    <img
                        src={unipokemon.image}
                        alt={`Pokemon ${unipokemon.name}`}  
                        width={150}
                        height={150}
                        className="bg-gray-100 pokemon-img group-hover:bg-gray-200 duration-300 group-hover:scale-[110%] rounded-full"  
                    />
                </div>
                <div>
                    <div className="flex flex-col space-y-2">
                    <div className="flex space-x-2">
                            <span className="font-bold capitalize italic">~ {unipokemon.name} ~</span>
                        </div>
                        <div className="flex space-x-2 text-sm text-gray-600">
                            <span className="font-bold">Altura:</span>
                            <span>{unipokemon.stats.height/10} m</span>
                        </div>
                        <div className="flex space-x-2 text-sm text-gray-600">
                            <span className="font-bold">Peso:</span>
                            <span>{unipokemon.stats.weight/10} kg</span>
                        </div>
                        <div className="flex space-x-2 text-sm text-gray-600">
                            <span className="font-bold">Experiencia base:</span>
                            <span>{unipokemon.stats.base_experience}</span>
                        </div>
                        <div className="flex space-x-2 text-sm">
                            {
                                unipokemon.types.map((type) => <span key={`${unipokemon.pokemonId}${type.name}`} className={`${type.name} text-white duration-300 rounded-full px-2 py-1`}>{type.name}</span>)
                            }
                        </div>
                    </div>
                </div>
                <div className="pt-2 col-span-2 grid grid-cols-3 w-full text-center [&>div]:duration-300 [&>div]:grid [&>div]:border-solid [&>div]:border-gray-200 [&>div>span:nth-child(2)]:text-gray-400 [&>div>span:nth-child(2)]:text-sm [&>div>span:nth-child(1)]:text-gray600 [&>div>span:nth-child(1)]:text-md">
                    <div className="hover:bg-green-100 rounded-tl-lg">
                        <span>{unipokemon.stats.hp}</span>
                        <span>HP</span>
                    </div>
                    <div className="border-x hover:bg-red-100">
                        <span>{unipokemon.stats.attack}</span>
                        <span>Attack</span>
                    </div>
                    <div className="hover:bg-amber-100 rounded-tr-lg">
                        <span>{unipokemon.stats.defense}</span>
                        <span>Defense</span>
                    </div>
                    <div className="border-t hover:bg-purple-100 rounded-bl-lg">
                        <span>{unipokemon.stats.speed}</span>
                        <span>Speed</span>
                    </div>
                    <div className="border-x hover:bg-red-100">
                        <span>{unipokemon.stats.special_attack}</span>
                        <span>Special Attack</span>
                    </div>
                    <div className="border-t hover:bg-orange-100 rounded-br-lg">
                        <span>{unipokemon.stats.special_defense}</span>
                        <span>Special Defense</span>
                    </div>
                </div>
            </div>
        </div>
    </AnimatedLink>)
}