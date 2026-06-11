@extends('layouts.admin')

@section('title', 'Create Service')

@section('content')
    <div class="mb-8">
        <p class="text-sm font-bold uppercase tracking-[0.25em] text-[#8a6f32]">Services</p>
        <h1 class="mt-3 font-serif text-4xl font-bold text-[#f0ece4]">Create Service</h1>
    </div>

    <form method="POST" action="{{ route('admin.services.store') }}" x-data="{ imageUrl: @js(old('image_url')) }" class="grid gap-6 lg:grid-cols-[1fr_320px]">
        @csrf
        <section class="border border-[#2a2a2a] bg-[#161616] p-6">
            <label class="block">
                <span class="text-sm text-[#f0ece4]">Name</span>
                <input name="name" value="{{ old('name') }}" required class="mt-2 h-12 w-full border border-[#2a2a2a] bg-[#1f1f1f] px-3 text-[#f0ece4] outline-none focus:border-[#c9a84c]">
                @error('name') <span class="mt-1 block text-sm text-[#b94a4a]">{{ $message }}</span> @enderror
            </label>

            <label class="mt-4 block">
                <span class="text-sm text-[#f0ece4]">Description</span>
                <textarea name="description" rows="5" class="mt-2 w-full border border-[#2a2a2a] bg-[#1f1f1f] px-3 py-3 text-[#f0ece4] outline-none focus:border-[#c9a84c]">{{ old('description') }}</textarea>
                @error('description') <span class="mt-1 block text-sm text-[#b94a4a]">{{ $message }}</span> @enderror
            </label>

            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <label class="block">
                    <span class="text-sm text-[#f0ece4]">Duration Minutes</span>
                    <input type="number" name="duration_minutes" value="{{ old('duration_minutes') }}" min="5" required class="mt-2 h-12 w-full border border-[#2a2a2a] bg-[#1f1f1f] px-3 text-[#f0ece4] outline-none focus:border-[#c9a84c]">
                    @error('duration_minutes') <span class="mt-1 block text-sm text-[#b94a4a]">{{ $message }}</span> @enderror
                </label>
                <label class="block">
                    <span class="text-sm text-[#f0ece4]">Price</span>
                    <input type="number" name="price" value="{{ old('price') }}" min="0" step="0.01" required class="mt-2 h-12 w-full border border-[#2a2a2a] bg-[#1f1f1f] px-3 text-[#f0ece4] outline-none focus:border-[#c9a84c]">
                    @error('price') <span class="mt-1 block text-sm text-[#b94a4a]">{{ $message }}</span> @enderror
                </label>
            </div>

            <label class="mt-4 block">
                <span class="text-sm text-[#f0ece4]">Image URL</span>
                <input type="url" name="image_url" x-model="imageUrl" class="mt-2 h-12 w-full border border-[#2a2a2a] bg-[#1f1f1f] px-3 text-[#f0ece4] outline-none focus:border-[#c9a84c]">
                @error('image_url') <span class="mt-1 block text-sm text-[#b94a4a]">{{ $message }}</span> @enderror
            </label>

            <label class="mt-5 flex items-center gap-3 text-sm text-[#f0ece4]/75">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true)) class="h-4 w-4">
                Active
            </label>

            <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                <button type="submit" class="h-12 bg-[#c9a84c] px-5 font-bold uppercase tracking-wide text-[#0e0e0e] transition duration-200 hover:bg-[#8a6f32]">Save Service</button>
                <a href="{{ route('admin.services.index') }}" class="inline-flex h-12 items-center justify-center border border-[#2a2a2a] px-5 font-bold uppercase tracking-wide text-[#f0ece4] transition duration-200 hover:border-[#c9a84c] hover:text-[#c9a84c]">Cancel</a>
            </div>
        </section>

        <aside class="border border-[#2a2a2a] bg-[#161616] p-6">
            <p class="text-sm font-bold uppercase tracking-wide text-[#8a6f32]">Preview</p>
            <img x-show="imageUrl" :src="imageUrl" alt="Service preview" class="mt-4 aspect-[4/3] w-full object-cover">
            <div x-show="!imageUrl" class="mt-4 flex aspect-[4/3] items-center justify-center border border-[#2a2a2a] text-sm text-[#6b6b6b]">No image URL</div>
        </aside>
    </form>
@endsection
