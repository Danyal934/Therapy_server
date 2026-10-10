@extends('layouts.app')

@section('title', 'About — LCI Wellbeing')
@section('meta_description', 'Leigh-Catherine Inglis is a trauma-informed psychotherapist and corporate trauma specialist.')

@section('content')
    <section class="bg-cream px-6 pt-16 pb-20 sm:pt-20">
        <div class="mx-auto grid max-w-6xl gap-12 lg:grid-cols-[0.85fr_1.15fr] lg:items-center">
            <div class="overflow-hidden rounded-[1.75rem] ring-1 ring-hairline shadow-[0_24px_50px_-20px_rgba(43,38,32,0.22)]">
                <img src="/images/LCI-portrait.png" alt="Leigh-Catherine Inglis" class="h-full w-full object-cover">
            </div>
            <div>
                <p class="font-sans text-xs font-semibold uppercase tracking-[0.2em] text-gold">About</p>
                <h1 class="mt-4 font-serif text-3xl font-semibold leading-tight text-ink sm:text-4xl">
                    Leigh-Catherine Inglis
                </h1>
                <p class="mt-2 font-sans text-sm uppercase tracking-[0.14em] text-ink-soft">
                    Psychotherapist &middot; Corporate Trauma Specialist &middot; Author &middot; Founder of LCI Wellbeing
                </p>
                <div class="mt-6 space-y-4 font-sans text-base leading-7 text-ink-soft sm:text-lg">
                    <p>
                        Leigh-Catherine Inglis is a trauma-informed psychotherapist and corporate
                        trauma specialist.
                    </p>
                    <p>
                        Her work supports individuals and organisations in navigating pressure,
                        processing difficult experiences, and building resilience in a grounded
                        and sustainable way.
                    </p>
                </div>
                <div class="mt-8">
                    <x-button href="{{ route('contact') }}">Get in Touch</x-button>
                </div>
            </div>
        </div>
    </section>
@endsection
