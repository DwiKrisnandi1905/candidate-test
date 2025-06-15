<aside class="w-60 h-screen bg-green-600 shadow-md fixed z-10">
    <div class="text-lg font-semibold text-white border-b border-green-500" style="padding: 18px;">
        CLT TOOLBOX
    </div>

    <nav class="mt-4 px-4 space-y-2">
        <a href="/dashboard"
           class="block px-4 py-2 rounded text-white hover:bg-green-500
           {{ request()->is('dashboard') ? 'bg-green-500 font-semibold' : '' }}">
            Dashboard
        </a>

        <a href="{{ route('project.index') }}"
           class="block px-4 py-2 rounded text-white hover:bg-green-500
           {{ request()->routeIs('project.*') ? 'bg-green-500 font-semibold' : '' }}">
            Project
        </a>
    </nav>
</aside>
