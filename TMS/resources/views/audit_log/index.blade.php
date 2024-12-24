<x-organization-layout>
    <div class="py-6 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline text-sm">
                        &larr; Go back
                    </a>
                    <h1 class="text-2xl font-semibold text-gray-800 mt-2">System Audit Log</h1>
                    <p class="text-gray-600 text-sm">
                        The System Audit Log enables administrators to monitor and review team activities, 
                        providing an audit trail to ensure accountability and transparency.
                    </p>
                </div>
                <form method="GET" action="{{ route('audit_log') }}" class="flex items-center space-x-4">
                <div class="text-sm text-gray-600">
                    <span class="font-medium">Activity Log for:</span>
                    <!-- Organization Filter -->
                    <select
                        name="organization_id"
                        class="border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2"
                        onchange="this.form.submit()"
                    >
                        <option value="">All Organizations</option>
                        @foreach($organizations as $organization)
                            <option value="{{ $organization->id }}" 
                                {{ request('organization_id') == $organization->id ? 'selected' : '' }}>
                                {{ $organization->registration_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Filter/Search Section -->
            <div class="flex justify-between items-center mb-6">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search"
                        class="border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-64 p-2"
                    />

                    <!-- General Filter -->
                    <select
                        name="filter"
                        class="border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2"
                    >
                        <option value="">All</option>
                        <option value="user" {{ request('filter') == 'user' ? 'selected' : '' }}>User</option>
                        <option value="page" {{ request('filter') == 'page' ? 'selected' : '' }}>Page</option>
                        <option value="activity" {{ request('filter') == 'activity' ? 'selected' : '' }}>Activity</option>
                        <option value="ip" {{ request('filter') == 'ip' ? 'selected' : '' }}>IP</option>
                        <option value="browser" {{ request('filter') == 'browser' ? 'selected' : '' }}>Browser</option>
                    </select>
                </form>
            </div>

            <!-- Table -->
            <div class="bg-white shadow-sm rounded-lg overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="bg-gray-100 text-gray-800">
                        <tr>
                            <th class="py-3 px-4">Timestamp</th>
                            <th class="py-3 px-4">User</th>
                            <th class="py-3 px-4">Page</th>
                            <th class="py-3 px-4">Activity</th>
                            <th class="py-3 px-4">Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activities as $activity)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-4">{{ $activity->created_at->format('Y-m-d H:i:s') }}</td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center space-x-2">
                                        <div>
                                            <div class="font-medium text-gray-900">
                                                {{ $activity->causer->name ?? 'Taxuri' }}
                                            </div>
                                            @if($activity->causer->role)
                                                <div class="text-sm text-gray-500">
                                                    {{ $activity->causer->role }}
                                                </div>
                                            @else
                                                <div class="text-sm text-gray-400 italic">
                                                    No role assigned
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-2">
                                    <span class="inline-block bg-gray-100 text-gray-700 text-xs font-medium rounded-full px-3 py-1">
                                        {{ $activity->log_name }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    {{ $activity->description }}     
                                    {{-- sample update showing --}}
                                    @if(isset($activity->properties['changes']) && is_array($activity->properties['changes']))
                                        <div class="mt-2 text-sm text-gray-700">
                                            <strong>Changes:</strong>
                                            <ul class="list-disc list-inside">
                                                @foreach($activity->properties['changes'] as $key => $change)
                                                    <li>
                                                        <strong>{{ ucfirst($key) }}:</strong>
                                                        <span class="text-red-500">{{ $change['old'] ?? 'N/A' }}</span> →
                                                        <span class="text-green-500">{{ $change['new'] ?? 'N/A' }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    {{-- for employee multi deleted --}}
                                    @if(isset($activity->properties['deleted_employees']) && is_array($activity->properties['deleted_employees']))
                                        <div class="mt-2">
                                            <strong>Deleted Employees:</strong>
                                            <ul class="list-disc list-inside">
                                                @foreach($activity->properties['deleted_employees'] as $employee)
                                                    <li>
                                                        {{ $employee['name'] }} 
                                                        <span class="text-xs">TIN: {{ $employee['tin'] }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-500">
                                    IP: {{ $activity->properties['ip'] ?? 'N/A' }},<br>
                                    Browser: {{ $activity->properties['browser'] ?? 'N/A' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-gray-500">
                                    No activity logs found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class=" mt-4">
                <div>
                    {{ $activities->links('vendor.pagination.custom') }}
                </div>
            </div>
        </div>
    </div>
</x-organization-layout>
