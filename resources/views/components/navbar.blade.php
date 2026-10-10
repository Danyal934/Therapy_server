<header class="sticky top-0 z-50 border-b border-hairline bg-paper/90 backdrop-blur-2xl shadow-md">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between gap-4">
            <a href="{{ route('home') }}" class="flex items-center gap-3 rounded-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-gold transition-all duration-300">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-gold/10 text-gold ring-1 ring-gold/20">
                        <img src="/images/LCI wellbeing logo.png" alt="LCI Wellbeing Logo" class="h-7 w-7 object-contain">
                    </span>
                    <div class="flex flex-col">
                        <span class="text-sm font-semibold tracking-tight text-ink">LCI Wellbeing</span>
                        <span class="text-[10px] uppercase tracking-[0.2em] text-gold">Come home to yourself</span>
                    </div>
                </a>

            <nav class="hidden items-center gap-1 sm:flex">
                <a href="{{ route('home') }}" class="rounded-xl px-3 py-2 text-sm font-medium transition hover:bg-gold/10 hover:text-ink @if(request()->routeIs('home')) bg-gold/15 text-gold @endif text-ink-soft">Home</a>
                <a href="{{ route('shop') }}" class="rounded-xl px-3 py-2 text-sm font-medium transition hover:bg-gold/10 hover:text-ink @if(request()->routeIs('shop')) bg-gold/15 text-gold @endif text-ink-soft">Shop</a>
                <a href="{{ route('about') }}" class="rounded-xl px-3 py-2 text-sm font-medium transition hover:bg-gold/10 hover:text-ink @if(request()->routeIs('about')) bg-gold/15 text-gold @endif text-ink-soft">About</a>
                <a href="{{ route('private-therapy') }}" class="rounded-xl px-3 py-2 text-sm font-medium transition hover:bg-gold/10 hover:text-ink @if(request()->routeIs('private-therapy')) bg-gold/15 text-gold @endif text-ink-soft">Private Therapy</a>
                <a href="{{ route('corporate-services') }}" class="rounded-xl px-3 py-2 text-sm font-medium transition hover:bg-gold/10 hover:text-ink @if(request()->routeIs('corporate-services')) bg-gold/15 text-gold @endif text-ink-soft">Corporate Services</a>
                <a href="{{ route('app') }}" class="rounded-xl px-3 py-2 text-sm font-medium transition hover:bg-gold/10 hover:text-ink @if(request()->routeIs('app')) bg-gold/15 text-gold @endif text-ink-soft">App</a>
                <a href="{{ route('contact') }}" class="ml-1 inline-flex items-center rounded-xl bg-ink px-4 py-2 text-sm font-medium text-paper shadow-sm transition hover:bg-ink/90 focus:outline-none focus-visible:ring-2 focus-visible:ring-gold">Contact</a>
            </nav>

            <details class="relative sm:hidden">
                <summary class="list-none rounded-xl border border-hairline bg-paper px-3 py-2 text-sm font-medium text-ink shadow-sm transition hover:bg-cream focus:outline-none focus-visible:ring-2 focus-visible:ring-gold">
                    Menu
                </summary>
                <div class="absolute right-0 mt-2 w-56 overflow-hidden rounded-2xl border border-hairline bg-paper shadow-lg">
                    <div class="flex flex-col p-2">
                        <a href="{{ route('home') }}" class="rounded-xl px-3 py-2 text-sm font-medium text-ink-soft transition hover:bg-gold/10 hover:text-ink @if(request()->routeIs('home')) bg-gold/15 text-gold @endif">Home</a>
                        <a href="{{ route('shop') }}" class="rounded-xl px-3 py-2 text-sm font-medium text-ink-soft transition hover:bg-gold/10 hover:text-ink @if(request()->routeIs('shop')) bg-gold/15 text-gold @endif">Shop</a>
                        <a href="{{ route('about') }}" class="rounded-xl px-3 py-2 text-sm font-medium text-ink-soft transition hover:bg-gold/10 hover:text-ink @if(request()->routeIs('about')) bg-gold/15 text-gold @endif">About</a>
                        <a href="{{ route('private-therapy') }}" class="rounded-xl px-3 py-2 text-sm font-medium text-ink-soft transition hover:bg-gold/10 hover:text-ink @if(request()->routeIs('private-therapy')) bg-gold/15 text-gold @endif">Private Therapy</a>
                        <a href="{{ route('corporate-services') }}" class="rounded-xl px-3 py-2 text-sm font-medium text-ink-soft transition hover:bg-gold/10 hover:text-ink @if(request()->routeIs('corporate-services')) bg-gold/15 text-gold @endif">Corporate Services</a>
                        <a href="{{ route('app') }}" class="rounded-xl px-3 py-2 text-sm font-medium text-ink-soft transition hover:bg-gold/10 hover:text-ink @if(request()->routeIs('app')) bg-gold/15 text-gold @endif">App</a>
                        <a href="{{ route('contact') }}" class="mt-1 rounded-xl bg-ink px-3 py-2 text-sm font-medium text-paper transition hover:bg-ink/90">Contact</a>
                    </div>
                </div>
            </details>
        </div>
    </div>
</header>

