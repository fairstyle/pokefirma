export const PokemonBasicStatsComponent = ({ pokemon }) => {
    
    return (<div className="pt-2 grid col-span-2 grid-cols-3 w-full text-center [&>div]:duration-300 [&>div]:grid [&>div]:border-solid [&>div]:border-gray-200 [&>div>span:nth-child(2)]:text-gray-400 [&>div>span:nth-child(2)]:text-sm [&>div>span:nth-child(1)]:text-gray600 [&>div>span:nth-child(1)]:text-md cursor-cell">
    <div className="hover:bg-green-100 rounded-tl-lg">
        <span>{pokemon.stats.hp}</span>
        <span>HP</span>
    </div>
    <div className="border-x hover:bg-red-100">
        <span>{pokemon.stats.attack}</span>
        <span>Attack</span>
    </div>
    <div className="hover:bg-amber-100 rounded-tr-lg">
        <span>{pokemon.stats.defense}</span>
        <span>Defense</span>
    </div>
    <div className="border-t hover:bg-purple-100 rounded-bl-lg">
        <span>{pokemon.stats.speed}</span>
        <span>Speed</span>
    </div>
    <div className="border-x hover:bg-red-100">
        <span>{pokemon.stats.special_attack}</span>
        <span>Special Attack</span>
    </div>
    <div className="border-t hover:bg-orange-100 rounded-br-lg">
        <span>{pokemon.stats.special_defense}</span>
        <span>Special Defense</span>
    </div>
</div>)
};