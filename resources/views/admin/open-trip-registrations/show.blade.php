@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Open Trip Registration Details</h1>
        <a href="{{ route('admin.open-trip-registrations.index') }}" class="text-gray-600 hover:text-gray-800">
            &larr; Back to List
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Participant Information</h2>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Name</label>
                        <p class="text-gray-900">{{ $openTripRegistration->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Email</label>
                        <p class="text-gray-900">{{ $openTripRegistration->email }}</p>
                    </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Phone</label>
                            <p class="text-gray-900">{{ $openTripRegistration->number_phone }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Date</label>
                            <p class="text-gray-900">{{ $openTripRegistration->date ? $openTripRegistration->date->format('d M Y') : '-' }}</p>
                        </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Institution</label>
                        <p class="text-gray-900">{{ $openTripRegistration->institution ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Registration Information</h2>
                <div class="space-y-3">
        <div>
            <label class="block text-sm font-medium text-gray-500">Schedule</label>
            <p class="text-gray-900">{{ $openTripRegistration->schedule->travelPackage->type ?? 'N/A' }}</p>
            @if($openTripRegistration->schedule)
                <p class="text-sm text-gray-500">
                    {{ $openTripRegistration->schedule->start_date->format('d M Y') }} - 
                    {{ $openTripRegistration->schedule->end_date->format('d M Y') }}
                </p>
            @endif
        </div>
         <div>
             <label class="block text-sm font-medium text-gray-500">Travel Package</label>
             <p class="text-gray-900">{{ $openTripRegistration->travelPackage->type ?? 'N/A' }}</p>
             <p class="text-sm text-gray-500">{{ $openTripRegistration->travelPackage->location ?? '' }}</p>
         </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">People Count</label>
                        <p class="text-gray-900">{{ $openTripRegistration->people_count }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Status</label>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $openTripRegistration->status_badge }}">
                            {{ ucfirst($openTripRegistration->status) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Registered At</label>
                        <p class="text-gray-900">{{ $openTripRegistration->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Notes</label>
                        <p class="text-gray-900">{{ $openTripRegistration->notes ?? '-' }}</p>
                    </div>
                    
                    @if($openTripRegistration->participants->count() > 0)
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-500 mb-2">Participants</label>
                        <div class="space-y-2">
                            @foreach($openTripRegistration->participants as $participant)
                                <div class="flex items-center gap-2 text-sm">
                                    <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                                    <span class="text-gray-900">{{ $participant->name }}</span>
                                    @if($participant->email)
                                        <span class="text-gray-500">({{ $participant->email }})</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="mt-6 pt-6 border-t">
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.open-trip-registrations.edit', $openTripRegistration) }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    Edit
                </a>
                
                <form method="POST" action="{{ route('admin.open-trip-registrations.destroy', $openTripRegistration) }}" 
                      onsubmit="return confirm('Are you sure you want to delete this registration?');" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection