import { PokemonInterface } from "../shared/interfaces/Pokemon.interface"
import { AnimatedLink } from "./AnimatedLink"
import { PokemonBasicStatsComponent } from "./PokemonBasicStats"
import { PokemonImageComponent } from "./PokemonImage"

export const PokemonCardComponent = (pokemon: any) => {
    const unipokemon: PokemonInterface = pokemon.pokemon

    return (<AnimatedLink to={`/pokemon/${unipokemon.pokemonId}`}>
        <div className="p-2 grid justify-items-center shadow-md rounded-lg relative hover:bg-gray-50 duration-300 group">
            <span className="text-xl text-gray-300 absolute left-2 top-2">{unipokemon.pokemonId}°</span>
            <div className="grid grid-cols-2 w-full py-2 pl-4">
                <div>
                    <PokemonImageComponent src={unipokemon.image} alt={`Pokemon ${unipokemon.name}`} width={150} height={150} classNames={`bg-gray-100 pokemon-img group-hover:bg-gray-200 duration-300 group-hover:scale-[110%] rounded-full`}/>
                </div>
                <div>
                    <div className="flex flex-col space-y-2">
                    <div className="flex space-x-2">
                            <span className="font-bold capitalize italic">~ {unipokemon.name.replaceAll("-", " ")} ~</span>
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
                <PokemonBasicStatsComponent pokemon={unipokemon}/>
            </div>
        </div>
    </AnimatedLink>)
}