<div class="min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md p-10 bg-white rounded-lg shadow-lg border border-gray-100">
        <div class="flex flex-col items-center mb-6">
            <img src="/logo.png" alt="Logo MPAP" class="h-28">
            <span class="mt-3 text-2xl font-bold text-gray-800">{{ config('app.name') }}</span>
        </div>
        <h1 class="text-lg font-medium text-center text-gray-800 mb-4">
            Efetue o login com sua conta da <span class="underline decoration-blue-500 decoration-4 underline-offset-4">Intranet</span>
        </h1>
        <form wire:submit.prevent="authenticate" class="space-y-6">
            <div>
                <label for="usuario" class="block text-sm font-medium text-gray-700 mb-2">Usuário</label>
                <input
                    id="usuario"
                    type="text"
                    wire:model="usuario"
                    class="w-full px-3 py-3 bg-gray-50 border border-gray-200 rounded-md text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 focus:outline-none"
                    placeholder="maria.eduarda"
                    autocomplete="username"
                >
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Senha</label>
                <input
                    id="password"
                    type="password"
                    wire:model="password"
                    class="w-full px-3 py-3 bg-gray-50 border border-gray-200 rounded-md text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 focus:outline-none"
                    placeholder="••••••••"
                    autocomplete="current-password"
                >
            </div>

            <button
                type="submit"
                class="w-full py-3 bg-blue-500 text-white font-medium rounded-md hover:bg-blue-600 transition duration-200 flex items-center justify-center"
                wire:loading.attr="disabled"
                wire:target="authenticate"
            >
                <span wire:loading.remove wire:target="authenticate">Entrar</span>
                <span wire:loading wire:target="authenticate" class="flex items-center justify-center">
                    Entrando...
                </span>
            </button>

        </form>
    </div>
</div>
