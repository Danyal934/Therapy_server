@extends('layouts.app')

@section('title', 'Contact — LCI Wellbeing')
@section('meta_description', 'If you feel ready to begin, get in touch to arrange an initial conversation.')

@section('content')
    <section class="bg-cream px-6 pt-16 pb-20 sm:pt-20">
        <div class="mx-auto max-w-2xl text-center">
            <p class="font-sans text-xs font-semibold uppercase tracking-[0.2em] text-gold">Contact</p>
            <h1 class="mt-4 font-serif text-3xl font-semibold text-ink sm:text-4xl">Let's begin the conversation.</h1>
            <p class="mt-4 font-sans text-base leading-7 text-ink-soft sm:text-lg">
                If you feel ready to begin, get in touch to arrange an initial conversation.
            </p>
        </div>

        <x-card as="div" class="mx-auto mt-10 max-w-2xl !p-8 sm:!p-10">
            <form method="POST" action="{{ route('contact.store') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="name" class="font-sans text-xs font-semibold uppercase tracking-[0.14em] text-ink">Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required
                        class="mt-2 w-full rounded-xl border border-hairline bg-cream px-4 py-3 font-sans text-sm text-ink shadow-sm transition focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/25">
                    @error('name')
                        <div class="mt-2 font-sans text-sm text-rose-700">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label for="email" class="font-sans text-xs font-semibold uppercase tracking-[0.14em] text-ink">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required
                        class="mt-2 w-full rounded-xl border border-hairline bg-cream px-4 py-3 font-sans text-sm text-ink shadow-sm transition focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/25">
                    @error('email')
                        <div class="mt-2 font-sans text-sm text-rose-700">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label for="message" class="font-sans text-xs font-semibold uppercase tracking-[0.14em] text-ink">Message</label>
                    <textarea id="message" name="message" rows="6" required
                        class="mt-2 w-full rounded-xl border border-hairline bg-cream px-4 py-3 font-sans text-sm text-ink shadow-sm transition focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/25">{{ old('message') }}</textarea>
                    @error('message')
                        <div class="mt-2 font-sans text-sm text-rose-700">{{ $message }}</div>
                    @enderror
                </div>

                <div class="pt-2 text-center">
                    <x-button type="submit">Send Message</x-button>
                </div>
            </form>
        </x-card>
    </section>
@endsection
