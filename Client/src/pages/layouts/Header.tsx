import { useState } from 'react'

export const HeaderPage = () => {
    const [flagBar, setAlterBar] = useState(false)

    const alterBar = () => {
        setAlterBar(!flagBar)
    }

    return (<header className="bg-gray-100 px-4 py-2 drop-shadow-md fixed w-full z-30">
        <div className="flex justify-between">
            <div className="w-fit">
                <a href="/pokemon"><h1 className="text-3xl font-black">Pokefirma</h1></a>
            </div>
            <div className="flex space-x-4">
                <div className="hidden md:block self-center">
                    <div>
                        <input className="rounded-lg bg-white w-64 p-2 focus:outline-gray-300 text-gray-400" type="text" placeholder="Buscar pokemon por nombre" />
                    </div>
                    <div className="relative">
                        <div className="absolute">buscar</div>
                    </div>
                </div>
                <div className="self-center hover:bg-gray-200 p-1 rounded-lg border border-dashed border-gray-300 cursor-pointer">
                    <div onClick={alterBar}>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="size-6">
                            <path strokeLinecap="round" strokeLinejoin="round" d="M3.75 5.25h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5" />
                        </svg>
                    </div>
                    <div className={`${flagBar ? "w-[100%] flex justify-center" : ""} bg-gray-100 w-[0] h-max min-h-screen z-20 fixed right-0 mt-4 duration-300 cursor-default`}>
                        <div>
                            <img 
                                src="https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/f/c734f9a3-d86f-4377-b639-87459c3202a0/d6o72vo-06187a6b-3483-4335-ac1a-64ba27816fbc.png?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1cm46YXBwOjdlMGQxODg5ODIyNjQzNzNhNWYwZDQxNWVhMGQyNmUwIiwiaXNzIjoidXJuOmFwcDo3ZTBkMTg4OTgyMjY0MzczYTVmMGQ0MTVlYTBkMjZlMCIsIm9iaiI6W1t7InBhdGgiOiJcL2ZcL2M3MzRmOWEzLWQ4NmYtNDM3Ny1iNjM5LTg3NDU5YzMyMDJhMFwvZDZvNzJ2by0wNjE4N2E2Yi0zNDgzLTQzMzUtYWMxYS02NGJhMjc4MTZmYmMucG5nIn1dXSwiYXVkIjpbInVybjpzZXJ2aWNlOmZpbGUuZG93bmxvYWQiXX0.5klIWcbAkvRse4cS8tpk1WBPzg1e92TsmCx46yOJw2Q" 
                                alt="user"
                                width={150}
                                height={150}
                                className='rounded-full' />
                            <div className="text-center">
                                <h2 className="text-2xl">Ash Firma</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>)
}