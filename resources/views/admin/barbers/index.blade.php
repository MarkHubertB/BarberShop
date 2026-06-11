@extends('layouts.admin')

@section('title', 'Barbers')

@section('content')
    <div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-sm font-bold uppercase tracking-[0.25em] text-[#8a6f32]">Admin</p>
            <h1 class="mt-3 font-serif text-4xl font-bold text-[#f0ece4]">Barbers</h1>
        </div>
        <a href="{{ route('admin.barbers.create') }}" class="inline-flex h-12 items-center justify-center bg-[#c9a84c] px-5 text-sm font-bold uppercase tracking-wide text-[#0e0e0e] transition duration-200 hover:bg-[#8a6f32]">New Barber</a>
    </div>

    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
        @foreach ($barbers as $barber)
            <article class="border border-[#2a2a2a] bg-[#161616]">
                <img src="{{ $barber->photo_url ?: 'https://placehold.co/400x500/161616/c9a84c?text=' . urlencode($barber->name) }}" alt="{{ $barber->name }}" class="aspect-[4/5] w-full object-cover">
                <div class="p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="font-serif text-2xl font-bold text-[#f0ece4]">{{ $barber->name }}</h2>
                            <p class="mt-2 text-sm text-[#c9a84c]">{{ $barber->specialty }}</p>
                        </div>
                        <form method="POST" action="{{ route('admin.barbers.update', $barber) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="_toggle_active" value="1">
                            <input type="hidden" name="is_active" value="{{ $barber->is_active ? 0 : 1 }}">
                            <button type="submit" class="border px-3 py-2 text-xs font-bold uppercase {{ $barber->is_active ? 'border-emerald-500/50 text-emerald-300' : 'border-[#b94a4a] text-[#b94a4a]' }}">
                                {{ $barber->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </form>
                    </div>
                    <p class="mt-4 min-h-20 leading-7 text-[#f0ece4]/65">{{ $barber->bio }}</p>

                    <div class="mt-5 flex flex-wrap gap-2">
                        <a href="{{ route('admin.barbers.edit', $barber) }}" class="border border-[#2a2a2a] px-3 py-2 text-sm text-[#f0ece4] transition duration-200 hover:border-[#c9a84c] hover:text-[#c9a84c]">Edit</a>
                        <form method="POST" action="{{ route('admin.barbers.destroy', $barber) }}" onsubmit="return confirm('Delete this barber?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="border border-[#b94a4a] px-3 py-2 text-sm text-[#b94a4a] transition duration-200 hover:bg-[#b94a4a] hover:text-[#0e0e0e]">Delete</button>
                        </form>
                    </div>
                </div>
            </article>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $barbers->links() }}
    </div>
@endsection
