<div @class([
    'flex flex-col items-center gap-4' => request()->routeIs('filament.admin.auth.*'),
    'flex items-center gap-2' => ! request()->routeIs('filament.admin.auth.*'),
])>
    <img src="{{ asset('logo.png') }}" alt="Logo do Ministério" @class([
        'w-30 h-20' => request()->routeIs('filament.admin.auth.*'),
        'w-30 h-10' => ! request()->routeIs('filament.admin.auth.*'),
    ])>

    <div @class([
        'text-1xl font-bold dark:text-white break-words max-w-[120px] leading-none' => ! request()->routeIs('filament.admin.auth.*'),
        'text-2xl font-bold dark:text-white text-center' => request()->routeIs('filament.admin.auth.*'),
    ])>
        {{ config('app.name') }}
    </div>
</div>