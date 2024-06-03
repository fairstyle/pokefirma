export const PokemonLeftInfoComponent = ({ pokemon }) => {
    
    console.log("aaa", pokemon)
    return (<div>
        <div className='space-y-2'>
            <div className='grid place-content-center col-span-2 lg:col-span-1'>
                <div className='flex justify-center'>
                    { pokemon.back && pokemon.back !== null ? <a href={`${pokemon.back.pokemonId}`}>
                        <div className='py-4 px-6 bg-gray-100 rounded-l-lg cursor-pointer hover:bg-gray-200'>
                            <div className='flex space-x-4 items-center'>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="size-6">
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                                </svg>
                                <div className='flex space-x-2 items-center'>
                                    <div>
                                        <img 
                                        src={pokemon.back.image} 
                                        alt={pokemon.back.name} 
                                        width={30}
                                        height={30}
                                        />
                                    </div>
                                    <div className='grid'>
                                        <span className='text-md text-gray-500'>{pokemon.back.name}</span>
                                        <span className='text-sm italic text-gray-400'>#{pokemon.back.pokemonId}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a> : <></>}
                    { pokemon.next && pokemon.next !== null ? <a href={`${pokemon.next.pokemonId}`}>
                        <div className='py-4 px-6 bg-gray-100 rounded-r-lg cursor-pointer hover:bg-gray-200'>
                            <div className='flex space-x-4 items-center'>
                                <div className='flex space-x-2 items-center'>
                                    <div className='grid'>
                                        <span className='text-md text-gray-500'>{pokemon.next.name}</span>
                                        <span className='text-sm italic text-gray-400'>#{pokemon.next.pokemonId}</span>
                                    </div>
                                    <div>
                                        <img 
                                        src={pokemon.next.image} 
                                        alt=""
                                        width={30}
                                        height={30}
                                        />
                                    </div>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="size-6">
                                    <path strokeLinecap="round" strokeLinejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                </svg>
                            </div>
                        </div>
                    </a> : <></>}
                    
                </div>
            </div>
            <div className='border border-solid border-gray-200 px-8 rounded-lg drop-shadow-sm hover:bg-gray-100 cursor-cell py-2 duration-300 col-span-2 lg:col-span-1'>
                <h2 className='text-xl font-bold text-center'> -- Habilidades --</h2>
                <ul className='border-t boder-solid border-gray-200 [&>*]:p-1 [&>*]:rounded-lg [&>*:hover]:bg-gray-300 duration-300'>
                    { pokemon.abilities && pokemon.abilities.map((ability, index) => (<li className="text-gray-500 text-lg capitalize" key={index}>{ability.name.replaceAll("-", " ")} {ability.isHidden === "1" ? <span className="rainbow tracking-wider text-sm">(Oculta)</span> : ""}</li>))}
                </ul>
            </div>
            <div className='col-span-2 border border-solid border-gray-200 px-8 rounded-lg drop-shadow-sm hover:bg-gray-100 cursor-cell py-2 duration-300'>
                <h2 className='text-xl font-bold text-center'> -- Evoluciones --</h2>
                <div className="flex space-x-2 border-t boder-solid border-gray-200 py-2 max-w-full overflow-x-auto">
                    { pokemon.evolutions && pokemon.evolutions.map((evolution, index:number) => (
                        <div key={`evolution_${index}`} className="flex items-center">
                            { index > 0 ? <div className="mr-2 text-center ">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="size-6">
                                <path strokeLinecap="round" strokeLinejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                            </svg>
                            <div className="grid">
                                <span className="text-gray-400 text-sm">{evolution.level}</span>
                                <span className="text-gray-400 text-[10px]">Nivel</span>
                            </div>
                            </div> : <div></div>}
                        <a href={`/pokemon/${evolution.pokemonId}`}><div key={index} className="w-[150px] h-[150px] min-w-[150px] justify-items-center bg-gray-200 hover:bg-gray-300 rounded-lg p-2 grid text-center justify-center duration-300">
                            <img 
                            src={evolution.image} 
                            alt={evolution.name}
                            width={80}
                            height={80} />
                            <span className="text-md text-gray-500">{evolution.name}</span>
                            <span className="text-sm text-gray-400">#{evolution.pokemonId}</span>
                        </div></a></div>)
                    )}
                </div>
            </div>
        </div>
    </div>)
};