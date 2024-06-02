export const HeaderPage = () => {
    return (<header className="bg-gray-100 px-4 py-2 drop-shadow-md">
        <div className="flex justify-between">
            <div className="w-fit">
                <a href="/pokemon"><h1 className="text-3xl font-black">Pokefirma</h1></a>
            </div>
            <div className="flex space-x-4">
                <div className="hidden md:block self-center">
                    <input className="rounded-lg bg-white w-64 p-2 focus:outline-gray-300 text-gray-400" type="text" placeholder="Buscar pokemon por nombre" />
                </div>
                <div className="self-center hover:bg-gray-200 p-1 rounded-lg border border-dashed border-gray-300 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="size-6">
                        <path strokeLinecap="round" strokeLinejoin="round" d="M3.75 5.25h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5" />
                    </svg>
                </div>
            </div>
        </div>
    </header>)
}