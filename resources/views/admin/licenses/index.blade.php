@extends('admin.layout')

@section('title', __('licenses.title'))
@section('page_title', __('licenses.page_title'))

@section('content')
<div class="max-w-6xl mx-auto mt-10 px-6 py-8 bg-white rounded-2xl shadow-md">

    {{-- 🔑 Upgrade License Form --}}
    <form action="{{ route('super_admin.licenses.upgrade') }}" method="POST" class="mb-8">
        @csrf
        <div class="flex flex-col md:flex-row md:items-center md:space-x-4">
            <div class="flex-1">
                <label class="text-sm font-medium text-gray-600 mb-1 block">
                    {{ __('licenses.upgrade_label') }}
                </label>
                <div class="flex flex-col md:flex-row md:space-x-4 gap-2">
                    <input type="text" name="upgrade_key" placeholder="{{ __('licenses.upgrade_placeholder') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    <button type="submit"
                        class="w-full  md:w-auto bg-blue-600 text-white font-medium px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                        {{ __('licenses.upgrade_button') }}
                    </button>
                </div>
            </div>
        </div>
    </form>

    {{-- ✅ Status Message --}}
    @if(session('status'))
    <div class="p-4 mb-6 rounded-lg text-center font-medium
            {{ str_contains(session('status'), '❌') ? 'bg-red-100 text-red-700 border border-red-300' : 'bg-green-100 text-green-700 border border-green-300' }}">
        {{ session('status') }}
    </div>
    @endif

    {{-- 🧾 Intro Section --}}
    <div class="mb-10 text-gray-700 leading-relaxed">
        <h2 class="text-2xl font-semibold text-gray-800 mb-3">{{ __('licenses.about_title') }}</h2>
        <p>{{ __('licenses.about_p1') }}</p>
        <p class="mt-3">{{ __('licenses.about_p2') }}</p>
        <p class="mt-3">{{ __('licenses.about_p3') }}</p>
    </div>

    {{-- 🧩 License Cards --}}
    <h2 class="text-2xl font-bold text-gray-800 text-center mb-8">{{ __('licenses.plans_title') }}</h2>

    <div class="grid md:grid-cols-3 gap-6">
        @foreach($licenses as $license)
        @php
        $helper = app(App\Helpers\SystemHelper::class);
        $checkli = $helper->hasLicenseType($license->_name);

        $features = json_decode($license->features, true);
        $totalAccounts = ($features['users'] ?? 0) + ($features['admins'] ?? 0);
        @endphp
        <div class="relative border rounded-2xl shadow-sm hover:shadow-lg  {{ $checkli ? 'bg-blue-200' : 'bg-gray-50' }} p-6 transition-all duration-300 flex flex-col items-center text-center">

            {{-- 💼 Plan name --}}
            <h3 class="text-xl font-bold text-blue-700 mb-3 uppercase tracking-wide">
                {{ $license->_name }}
            </h3>

            {{-- 📊 Feature summary --}}
            <div class="text-gray-600 mb-5 space-y-2">
                <p><strong>{{ $totalAccounts }}</strong> {{ __('licenses.total_accounts') }}</p>
                <p><strong>{{ $features['admins'] ?? '-' }}</strong> {{ __('licenses.admins_max') }}</p>
                <p><strong>{{ $features['users'] ?? '-' }}</strong> {{ __('licenses.users_max') }}</p>
                <p>
                    {{ __('licenses.storage_text', ['storage' => $features['storage'] ?? '-']) }}
                </p>
            </div>


            {{-- 💬 Description --}}
            <p class="text-sm text-gray-500 mb-6">
                @can('isBasic')
                {{ __('licenses.desc_basic') }}
                @endcan
                @can('isPro')
                {{ __('licenses.desc_pro') }}
                @endcan
                @can('isEnterprise')
                {{ __('licenses.desc_enterprise') }}
                @endcan
            </p>


        </div>



        @endforeach
    </div>
</div>
@endsection