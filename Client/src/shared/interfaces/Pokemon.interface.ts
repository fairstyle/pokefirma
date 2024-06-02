export interface PokemonInterface {
    pokemonId: number;
    name: string;
    image: string;
    stats: Array<{
        height: number,
        weigth: number,
        base_experience: string
    }>;
    types: Array<{
        pokemonTypeId: string;
        name: string;
    }>;
}