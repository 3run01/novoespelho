<div class="bg-white dark:bg-gray-600 p-4 rounded-lg shadow-md center  mt-4" 
    x-data="{ 
        inicio: localStorage.getItem('periodo_inicio') || '-',
        fim: localStorage.getItem('periodo_fim') || '-'
    }">
    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">Periodo Selecionado</label>
    <div class="space-y-2">
        <div class="relative">
            <div class="mt-2 p-2 bg-gray-50 dark:bg-gray-700 rounded-md">
                <p class="text-xs text-gray-600 dark:text-gray-300">
                    De: <span x-text="inicio" class="font-medium"></span> - Até: <span x-text="fim" class="font-medium"></span>
                </p>
            </div>
        </div>
    </div>
</div>