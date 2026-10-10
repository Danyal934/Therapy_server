@extends('layouts.app')

@section('title', 'Corporate Services — LCI Wellbeing')
@section('meta_description', 'Specialist support to organisations navigating workplace trauma, critical incidents, and high-pressure environments.')

@section('content')

    {{-- HERO --}}
    <section class="bg-cream px-6 pt-16 pb-16 sm:pt-20">
        <div class="mx-auto max-w-4xl">
            <p class="font-sans text-xs font-semibold uppercase tracking-[0.2em] text-gold">Corporate Services</p>
            <h1 class="mt-4 font-serif text-3xl font-semibold leading-tight text-ink sm:text-4xl">
                Steady, specialist support for organisations under pressure.
            </h1>
            <p class="mt-3 font-script italic text-xl text-gold">Come home to yourself, together.</p>
            <div class="mt-6 space-y-4 font-sans text-base leading-7 text-ink-soft sm:text-lg">
                <p>
                    I provide specialist support to organisations navigating workplace trauma,
                    critical incidents, and high-pressure environments.
                </p>
                <p>
                    My work focuses on supporting both individuals and teams to process difficult
                    experiences, restore stability, and move forward in a contained and
                    professional way.
                </p>
            </div>
        </div>
    </section>

    {{-- HOW I SUPPORT --}}
    <x-section tone="paper" eyebrow="Services" title="How I support organisations">
        <div class="grid gap-6 lg:grid-cols-2">
            <x-card>
                <p class="font-sans text-xs font-semibold uppercase tracking-[0.14em] text-gold">01</p>
                <h3 class="mt-3 font-serif text-lg font-semibold text-ink">Critical Incident Response</h3>
                <div class="mt-3 space-y-2 font-sans text-sm leading-6 text-ink-soft">
                    <p>On-site or remote support following distressing or traumatic events in the workplace.</p>
                    <p>Providing immediate psychological support to help stabilise individuals and teams.</p>
                </div>
            </x-card>
            <x-card>
                <p class="font-sans text-xs font-semibold uppercase tracking-[0.14em] text-gold">02</p>
                <h3 class="mt-3 font-serif text-lg font-semibold text-ink">Workplace Trauma Support</h3>
                <div class="mt-3 space-y-2 font-sans text-sm leading-6 text-ink-soft">
                    <p>Ongoing support for employees experiencing the impact of stress, trauma, or emotionally demanding roles.</p>
                    <p>Creating space for processing, reflection, and recovery.</p>
                </div>
            </x-card>
            <x-card>
                <p class="font-sans text-xs font-semibold uppercase tracking-[0.14em] text-gold">03</p>
                <h3 class="mt-3 font-serif text-lg font-semibold text-ink">Workshops &amp; Psychoeducation</h3>
                <div class="mt-3 space-y-2 font-sans text-sm leading-6 text-ink-soft">
                    <p>Structured sessions focused on:</p>
                    <ul class="list-disc space-y-1 pl-5">
                        <li>Resilience</li>
                        <li>Emotional wellbeing</li>
                        <li>Managing pressure in high-performance environments</li>
                    </ul>
                    <p>Delivered in a clear, grounded, and practical way.</p>
                </div>
            </x-card>
            <x-card>
                <p class="font-sans text-xs font-semibold uppercase tracking-[0.14em] text-gold">04</p>
                <h3 class="mt-3 font-serif text-lg font-semibold text-ink">Leadership Support</h3>
                <div class="mt-3 space-y-2 font-sans text-sm leading-6 text-ink-soft">
                    <p>Guidance for managers and leaders on how to support their teams effectively during challenging periods.</p>
                    <p>Helping leaders respond with clarity, confidence, and care.</p>
                </div>
            </x-card>
        </div>
    </x-section>

    {{-- WHO / APPROACH --}}
    <x-section eyebrow="Fit &amp; Philosophy" title="Who I work with">
        <div class="grid gap-10 lg:grid-cols-2">
            <div>
                <p class="font-sans text-base leading-7 text-ink-soft sm:text-lg">
                    I work with organisations where employees are operating under pressure,
                    including corporate environments, public sector services, and
                    high-responsibility roles. My approach is particularly suited to teams
                    navigating change, stress, or critical incidents.
                </p>
            </div>
            <div>
                <h3 class="font-serif text-lg font-semibold text-ink">Approach</h3>
                <p class="mt-3 font-sans text-base leading-7 text-ink-soft sm:text-lg">
                    My work is trauma-informed, structured, and focused on creating psychological
                    safety. I bring a calm, steady presence into environments that require
                    clarity, containment, and professionalism.
                </p>
            </div>
        </div>
    </x-section>

    {{-- CTA --}}
    <section class="bg-cream-deep px-6 py-16">
        <div class="mx-auto max-w-4xl rounded-[2rem] bg-paper/60 backdrop-blur-xl p-10 ring-1 ring-hairline/70 shadow-[0_25px_50px_-20px_rgba(43,38,32,0.25)] sm:p-12">
            <h2 class="font-serif text-2xl font-semibold text-ink sm:text-3xl">Discuss support for your organisation</h2>
            <p class="mt-3 font-sans text-base leading-7 text-ink-soft sm:text-lg">
                For corporate enquiries or to discuss support for your organisation, please get
                in touch.
            </p>
            <p class="mt-3 font-script italic text-lg text-gold">Come home to yourself, together.</p>
            <div class="mt-7">
                <x-button href="{{ route('contact') }}">Get in Touch</x-button>
            </div>
        </div>
    </section>

@endsection
