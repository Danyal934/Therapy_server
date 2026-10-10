@extends('layouts.app')

@section('title', 'LCI Wellbeing — Come home to yourself')
@section('meta_description', 'Specialist therapy and trauma-informed support for individuals and organisations navigating life\'s most challenging moments.')

@section('content')

    {{-- HERO --}}
    <section class="relative overflow-hidden bg-cream px-6 pt-16 pb-20 sm:pt-24 sm:pb-28">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top_right,_theme(colors.cream-deep)_0%,_transparent_55%)]"></div>

        <div class="relative mx-auto grid max-w-6xl gap-14 lg:grid-cols-[1.15fr_0.85fr] lg:items-center">
            <div>
                <div class="flex items-center gap-3">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gold/10 text-gold ring-1 ring-gold/20">
                        <img src="/images/LCI wellbeing logo.png" alt="LCI Wellbeing Logo" class="h-10 w-10 object-contain">
                    </div>
                    <div>
                        <span class="block font-serif text-lg font-semibold text-ink">LCI Wellbeing</span>
                        <span class="text-[10px] uppercase tracking-[0.2em] text-gold">Come home to yourself</span>
                    </div>
                </div>

                <p class="mt-6 font-sans text-xs font-semibold uppercase tracking-[0.2em] text-gold">
                    Trauma-Informed Therapy &amp; Workplace Wellbeing
                </p>

                <h1 class="mt-5 font-serif text-4xl font-semibold leading-[1.12] text-ink sm:text-5xl lg:text-[3.4rem]">
                    Helping individuals and organisations navigate life's most challenging moments.
                </h1>

                <p class="mt-6 font-script italic text-2xl text-gold">Come home to yourself.</p>

                <p class="mt-6 max-w-xl font-sans text-base leading-7 text-ink-soft sm:text-lg">
                    Specialist support for burnout, trauma, grief, anxiety and emotional overwhelm.
                </p>

                <div class="mt-9 flex flex-wrap items-center gap-4">
                    <x-button href="{{ route('private-therapy') }}" variant="primary">Therapy for Individuals</x-button>
                    <x-button href="{{ route('corporate-services') }}" variant="outline">Corporate Support</x-button>
                </div>
            </div>

            <div class="relative">
                <div class="absolute -inset-4 -z-10 rounded-[2rem] bg-gold/10 blur-2xl"></div>
                <div class="overflow-hidden rounded-[1.75rem] ring-1 ring-hairline shadow-[0_30px_60px_-20px_rgba(43,38,32,0.25)]">
                    <img src="/images/LCI-portrait.png" alt="Leigh-Catherine Inglis, Psychotherapist" class="h-full w-full object-cover">
                </div>
                <div class="absolute -bottom-6 left-1/2 w-[85%] -translate-x-1/2 rounded-2xl bg-paper/90 backdrop-blur-2xl px-6 py-4 text-center ring-1 ring-hairline shadow-[0_16px_40px_-16px_rgba(43,38,32,0.3)]">
                    <p class="font-serif text-sm font-semibold text-ink">Leigh-Catherine Inglis</p>
                    <p class="mt-1 font-sans text-xs uppercase tracking-[0.14em] text-ink-soft">
                        Psychotherapist &middot; Corporate Trauma Specialist
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- PROFESSIONAL AUTHORITY --}}
    <section class="border-y border-hairline bg-cream-deep px-6 py-10">
        <div class="mx-auto max-w-6xl">
            <div class="grid gap-8 lg:grid-cols-2 lg:items-center">
                <div>
                    <p class="font-sans text-xs font-semibold uppercase tracking-[0.2em] text-gold">Professional Credentials</p>
                    <h2 class="mt-3 font-serif text-2xl font-semibold text-ink sm:text-3xl">Leigh-Catherine Inglis</h2>
                    <p class="mt-4 font-sans text-base leading-7 text-ink-soft sm:text-lg">
                        An accredited psychotherapist, corporate trauma consultant and CISM qualified professional with significant experience supporting organisations following critical incidents.
                    </p>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    @foreach([
                        ['label' => 'Accredited Psychotherapist', 'icon' => 'badge'],
                        ['label' => 'Corporate Trauma Consultant', 'icon' => 'briefcase'],
                        ['label' => 'CISM Qualified', 'icon' => 'shield'],
                        ['label' => 'Critical Incident Support', 'icon' => 'support'],
                    ] as $cred)
                        <div class="flex items-start gap-3 rounded-2xl bg-paper/60 backdrop-blur-xl p-4 ring-1 ring-hairline/70 shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gold/10 text-gold ring-1 ring-gold/20">
                                @if($cred['icon'] === 'badge')
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                @elseif($cred['icon'] === 'briefcase')
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg>
                                @elseif($cred['icon'] === 'shield')
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                @else
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                @endif
                            </div>
                            <p class="font-serif text-sm font-semibold text-ink">{{ $cred['label'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- PATHWAYS --}}
    <x-section eyebrow="Three Ways I Support" title="Find the right pathway">
        <div class="grid gap-6 lg:grid-cols-3">
            <x-card as="a" href="{{ route('private-therapy') }}" class="block">
                <h3 class="font-serif text-lg font-semibold text-ink">Private Therapy</h3>
                <p class="mt-3 font-sans text-sm leading-6 text-ink-soft">
                    A confidential space to explore what you're experiencing and begin to work
                    through it safely.
                </p>
                <div class="mt-5">
                    <x-button href="{{ route('private-therapy') }}" variant="primary" class="!w-full justify-center">Enquire about therapy</x-button>
                </div>
            </x-card>

            <x-card as="a" href="{{ route('corporate-services') }}" class="block">
                <h3 class="font-serif text-lg font-semibold text-ink">Corporate Services</h3>
                <p class="mt-3 font-sans text-sm leading-6 text-ink-soft">
                    Specialist support for organisations navigating workplace trauma, critical
                    incidents and high-pressure environments.
                </p>
                <div class="mt-5">
                    <x-button href="{{ route('corporate-services') }}" variant="primary" class="!w-full justify-center">Discuss organisational support</x-button>
                </div>
            </x-card>

            <x-card as="a" href="{{ route('app') }}" class="block">
                <h3 class="font-serif text-lg font-semibold text-ink">The LCI Wellbeing Journal App</h3>
                <p class="mt-3 font-sans text-sm leading-6 text-ink-soft">
                    A guided journaling experience designed to support emotional regulation,
                    reflection and reconnection.
                </p>
                <div class="mt-5">
                    <x-button href="{{ route('app') }}" variant="primary" class="!w-full justify-center">Explore the journal/app</x-button>
                </div>
            </x-card>
        </div>
    </x-section>

    {{-- ABOUT --}}
    <x-section tone="paper" eyebrow="About" title="Leigh-Catherine Inglis">
        <div class="grid gap-10 lg:grid-cols-[0.9fr_1.1fr] lg:items-center">
            <div class="overflow-hidden rounded-2xl ring-1 ring-hairline">
                <img src="/images/LCI-portrait.png" alt="Leigh-Catherine Inglis" class="h-full w-full object-cover">
            </div>
            <div class="space-y-4 font-sans text-base leading-7 text-ink-soft sm:text-lg">
                <p>
                    Leigh-Catherine Inglis is a trauma-informed psychotherapist and corporate
                    trauma specialist, author and founder of LCI Wellbeing.
                </p>
                <p>
                    Her work supports individuals and organisations in navigating pressure,
                    processing difficult experiences, and building resilience in a grounded and
                    sustainable way.
                </p>
                <x-button href="{{ route('about') }}" variant="ghost">Learn more about me &rarr;</x-button>
            </div>
        </div>
    </x-section>

    {{-- CLOSING CTA --}}
    <section class="bg-cream-deep px-6 py-20">
        <div class="mx-auto max-w-3xl rounded-[2rem] bg-paper/60 backdrop-blur-xl p-10 text-center ring-1 ring-hairline/70 shadow-[0_25px_50px_-20px_rgba(43,38,32,0.25)] sm:p-14">
            <p class="font-script italic text-2xl text-gold">You don't have to carry it all alone.</p>
            <h2 class="mt-4 font-serif text-2xl font-semibold text-ink sm:text-3xl">
                Support is here. You are not alone.
            </h2>
            <p class="mt-4 font-sans text-base leading-7 text-ink-soft">
                If you feel ready to begin, get in touch to arrange an initial conversation.
            </p>
            <p class="mt-3 font-script italic text-lg text-gold">Come home to yourself.</p>
            <div class="mt-8">
                <x-button href="{{ route('contact') }}">Begin Your Journey</x-button>
            </div>
        </div>
    </section>

@endsection
