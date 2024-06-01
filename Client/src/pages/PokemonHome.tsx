export const PokemonHomePage = () => {

    const getPokemons = async () => {
        
        const res = await fetch(import.meta.env.VITE_POKEAPI_LOCAL_URI+'/api/pokemon')
        const data = await res.json()

        console.log(data)
        return data
    }
    getPokemons()

    return (<div>Pokemon home page</div>)
}