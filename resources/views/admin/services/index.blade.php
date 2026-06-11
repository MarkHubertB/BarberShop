@extends('layouts.admin')

@section('title', 'Services')

@section('content')
    <div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-sm font-bold uppercase tracking-[0.25em] text-[#8a6f32]">Admin</p>
            <h1 class="mt-3 font-serif text-4xl font-bold text-[#f0ece4]">Services</h1>
        </div>
        <a href="{{ route('admin.services.create') }}" class="inline-flex h-12 items-center justify-center bg-[#c9a84c] px-5 text-sm font-bold uppercase tracking-wide text-[#0e0e0e] transition duration-200 hover:bg-[#8a6f32]">New Service</a>
    </div>

    <section class="border border-[#2a2a2a] bg-[#161616]">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[850px] text-left text-sm">
                <thead class="border-b border-[#2a2a2a] text-xs uppercase tracking-wide text-[#8a6f32]">
                    <tr>
                        <th class="px-5 py-4">Name</th>
                        <th class="px-5 py-4">Price</th>
                        <th class="px-5 py-4">Duration</th>
                        <th class="px-5 py-4">Active</th>
                        <th class="px-5 py-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#2a2a2a]">
                    @foreach ($services as $service)
                        <tr>
                            <td class="px-5 py-4">
                                <div class="font-serif text-xl font-bold text-[#f0ece4]">{{ $service->name }}</div>
                                <div class="mt-1 max-w-xl text-xs text-[#6b6b6b]">{{ $service->description }}</div>
                            </td>
                            <td class="px-5 py-4 text-[#c9a84c]">{{ number_format($service->price, 0) }}</td>
                            <td class="px-5 py-4 text-[#f0ece4]/75">{{ $service->duration_minutes }} min</td>
                            <td class="px-5 py-4">
                                <form method="POST" action="{{ route('admin.services.update', $service) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="_toggle_active" value="1">
                                    <input type="hidden" name="is_active" value="{{ $service->is_active ? 0 : 1 }}">
                                    <button type="submit" class="border px-3 py-2 text-xs font-bold uppercase {{ $service->is_active ? 'border-emerald-500/50 text-emerald-300' : 'border-[#b94a4a] text-[#b94a4a]' }}">
                                        {{ $service->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('admin.services.edit', $service) }}" class="border border-[#2a2a2a] px-3 py-2 text-[#f0ece4] transition duration-200 hover:border-[#c9a84c] hover:text-[#c9a84c]">Edit</a>
                                    <form method="POST" action="{{ route('admin.services.destroy', $service) }}" onsubmit="return confirm('Delete this service?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="border border-[#b94a4a] px-3 py-2 text-[#b94a4a] transition duration-200 hover:bg-[#b94a4a] hover:text-[#0e0e0e]">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <div class="mt-6">
        {{ $services->links() }}
    </div>
@endsection
