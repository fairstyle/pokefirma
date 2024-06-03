export interface PokemonInterface {
    pokemonId: number;
    name: string;
    image: string;
    isLoaded: true;
    stats: Array<{
        height: number,
        weigth: number,
        base_experience: string,
        hp: number,
        attack: number,
        defense: number,
        special_attack: number,
        special_defense: number,
        speed: number
    }>;
    types: Array<{
        pokemonTypeId: string;
        name: string;
    }>;
    abilities: Array<{
        pokemonAbilityId: string;
        name: string;
        isHidden: string;
    }>;
}