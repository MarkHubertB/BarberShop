@extends('layouts.admin')

@section('title', 'Edit Barber')

@section('content')
    <div class="mb-8">
        <p class="text-sm font-bold uppercase tracking-[0.25em] text-[#8a6f32]">Barbers</p>
        <h1 class="mt-3 font-serif text-4xl font-bold text-[#f0ece4]">Edit Barber</h1>
    </div>

    <form method="POST" action="{{ route('admin.barbers.update', $barber) }}" x-data="{ photoUrl: @js(old('photo_url', $barber->photo_url)) }" class="grid gap-6 lg:grid-cols-[1fr_320px]">
        @csrf
        @method('PATCH')
        <section class="border border-[#2a2a2a] bg-[#161616] p-6">
            <label class="block">
                <span class="text-sm text-[#f0ece4]">Name</span>
                <input name="name" value="{{ old('name', $barber->name) }}" required class="mt-2 h-12 w-full border border-[#2a2a2a] bg-[#1f1f1f] px-3 text-[#f0ece4] outline-none focus:border-[#c9a84c]">
                @error('name') <span class="mt-1 block text-sm text-[#b94a4a]">{{ $message }}</span> @enderror
            </label>

            <label class="mt-4 block">
                <span class="text-sm text-[#f0ece4]">Specialty</span>
                <input name="specialty" value="{{ old('specialty', $barber->specialty) }}" class="mt-2 h-12 w-full border border-[#2a2a2a] bg-[#1f1f1f] px-3 text-[#f0ece4] outline-none focus:border-[#c9a84c]">
                @error('specialty') <span class="mt-1 block text-sm text-[#b94a4a]">{{ $message }}</span> @enderror
            </label>

            <label class="mt-4 block">
                <span class="text-sm text-[#f0ece4]">Bio</span>
                <textarea name="bio" rows="6" class="mt-2 w-full border border-[#2a2a2a] bg-[#1f1f1f] px-3 py-3 text-[#f0ece4] outline-none focus:border-[#c9a84c]">{{ old('bio', $barber->bio) }}</textarea>
                @error('bio') <span class="mt-1 block text-sm text-[#b94a4a]">{{ $message }}</span> @enderror
            </label>

            <label class="mt-4 block">
                <span class="text-sm text-[#f0ece4]">Photo URL</span>
                <input type="url" name="photo_url" x-model="photoUrl" class="mt-2 h-12 w-full border border-[#2a2a2a] bg-[#1f1f1f] px-3 text-[#f0ece4] outline-none focus:border-[#c9a84c]">
                @error('photo_url') <span class="mt-1 block text-sm text-[#b94a4a]">{{ $message }}</span> @enderror
            </label>

            <label class="mt-5 flex items-center gap-3 text-sm text-[#f0ece4]/75">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $barber->is_active)) class="h-4 w-4">
                Active
            </label>

            <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                <button type="submit" class="h-12 bg-[#c9a84c] px-5 font-bold uppercase tracking-wide text-[#0e0e0e] transition duration-200 hover:bg-[#8a6f32]">Update Barber</button>
                <a href="{{ route('admin.barbers.index') }}" class="inline-flex h-12 items-center justify-center border border-[#2a2a2a] px-5 font-bold uppercase tracking-wide text-[#f0ece4] transition duration-200 hover:border-[#c9a84c] hover:text-[#c9a84c]">Cancel</a>
            </div>
        </section>

        <aside class="border border-[#2a2a2a] bg-[#161616] p-6">
            <p class="text-sm font-bold uppercase tracking-wide text-[#8a6f32]">Preview</p>
            <img x-show="photoUrl" :src="photoUrl" alt="Barber preview" class="mt-4 aspect-[4/5] w-full object-cover">
            <div x-show="!photoUrl" class="mt-4 flex aspect-[4/5] items-center justify-center border border-[#2a2a2a] text-sm text-[#6b6b6b]">No photo URL</div>
        </aside>
    </form>
@endsection
