@props(['icon', 'active' => false])

<a {{ $attributes }} class="{{ $active ? 'bg-primary text-white shadow-md shadow-blue-500/20' : 'text-slate-400 hover:bg-navy-800 hover:text-white' }} flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all font-medium text-[13.5px] cursor-pointer w-full">
    <i class="fa-solid {{ $icon }} w-5 text-center text-[15px]"></i>
    <span class="flex-1 truncate">{{ $slot }}</span>
</a>
