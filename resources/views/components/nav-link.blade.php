@props(['icon', 'active' => false])

<a {{ $attributes }} class="{{ $active ? 'bg-primary text-white shadow-lg shadow-blue-500/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }} flex items-center gap-3 px-5 py-3.5 rounded-2xl transition-all font-medium text-sm mb-1">
    <i class="fa-solid {{ $icon }} w-5 text-center"></i>
    {{ $slot }}
</a>