@props(['icon', 'active' => false])

<a {{ $attributes }} class="nav-item flex items-center gap-3 px-5 py-3.5 rounded-2xl transition-all font-medium text-sm {{ $active ? 'active' : 'inactive' }}">
    <i class="fa-solid {{ $icon }} w-5 text-center"></i>
    {{ $slot }}
</a>
