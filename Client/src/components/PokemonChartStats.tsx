import { Radar } from "react-chartjs-2";
import { Chart as ChartJS, RadialLinearScale, PointElement, LineElement, Filler, Tooltip} from "chart.js";
import { PokemonInterface } from "../shared/interfaces/Pokemon.interface";
import { PokemonBasicStatsComponent } from "./PokemonBasicStats";

ChartJS.register( RadialLinearScale, PointElement, LineElement, Filler, Tooltip);

export const PokemonChartStatsComponent = (pokemon: Array<PokemonInterface>) => { 

    const pokeuniq:PokemonInterface = pokemon.pokemon

    if(pokeuniq === undefined || Object.keys(pokeuniq).length === 0) return (<div className="grid place-content-center h-full">
        <span className="text-md text-gray-300">Cargando Información...</span>
    </div>)
   
    const data = {
        labels: [
            "HP",
            "Attack",
            "Defense",
            "S. Attack",
            "S. Defense",
            "Speed",
        ],
        datasets: [
            {
                label: `Estadísticas de ${pokeuniq.name}`,
                data: [pokeuniq.stats.hp ?? 0, pokeuniq.stats.attack, pokeuniq.stats.defense, pokeuniq.stats.special_attack, pokeuniq.stats.special_defense, pokeuniq.stats.speed],
                backgroundColor: "rgba(255, 99, 132, 0.2)",
                borderColor: "rgba(255, 99, 132, 1)",
                borderWidth: 1,
            },
        ],
        };

        const options = {
            scales: {
              r: {
                pointLabels: {
                  display: true, // Muestra las etiquetas de las estadísticas (HP, Attack, etc.)
                  font: {
                    size: 14, // Tamaño de la fuente
                    family: 'Arial', // Tipo de fuente
                    style: 'italic', // Estilo de la fuente
                    weight: 'bold', // Peso de la fuente
                  },
                  color: 'rgba(54, 162, 235, 1)',
                },
                ticks: {
                  display: false, // Oculta los valores de las etiquetas de los ticks
                },
              },
            },
            plugins: {
              legend: {
                display: false, // Oculta la leyenda
              },
              tooltip: {
                enabled: true, // Desactiva el tooltip si no lo necesitas
              },
            },
            maintainAspectRatio: true,
            responsive: true
          };
    
        return (
            <div className="space-y-2 h-full">
                <PokemonBasicStatsComponent pokemon={pokeuniq}/>
                <div className="border-t border-solid boder-gray-100 pt-2 w-full max-h-[350px] grid justify-center">
                    <Radar data={data} options={options} />
                </div>
            </div>
        );
    
}