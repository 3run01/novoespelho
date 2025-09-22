<div>
    <div class="text-center pt-10 text-3xl">
        <div>
            <h1 class='text-gray-700 text-6xl mb-8'>login</h1>
            <label for="email">Usuario</label>
            <div>
                <input
                    id="usuario"
                    type="text"
                    wire:model="usuario"
                    class='border-red-blue rounded-md'
                >
            </div>
            <label for="password">Senha</label>
            <div>
                <input
                    id="password"
                    type="password"
                    wire:model="password"
                    class='border-red-blue rounded-md'
                >
            </div>

            <button
                type="button"
                class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600"
                wire:click="authenticate"
                
            >
                LoginIntranet
            </button>
        </div>
    </div>
</div>
