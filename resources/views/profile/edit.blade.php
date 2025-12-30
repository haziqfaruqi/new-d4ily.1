<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - D4ily.1</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-zinc-50">
@include('partials.navigation')

<div class="max-w-4xl mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-zinc-900">My Profile</h1>
        <p class="text-zinc-600">Manage your account information and preferences</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-emerald-50 border border-emerald-200">
            <div class="flex items-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
                <p class="text-sm text-emerald-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200">
            <div class="flex items-start gap-2">
                <i data-lucide="alert-circle" class="w-4 h-4 text-red-600 mt-0.5"></i>
                <div class="text-sm text-red-800">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <div class="grid lg:grid-cols-3 gap-8">
        <!-- Sidebar Navigation -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg border border-zinc-200 p-4">
                <div class="text-center mb-6">
                    <div class="h-20 w-20 rounded-full bg-zinc-900 flex items-center justify-center text-2xl font-bold text-white mx-auto mb-3 overflow-hidden">
                        @if($user->avatar)
                            <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                        @else
                            {{ substr($user->name, 0, 1) }}
                        @endif
                    </div>
                    <h3 class="font-semibold text-zinc-900">{{ $user->name }}</h3>
                    <p class="text-sm text-zinc-500">{{ $user->email }}</p>
                </div>
                <nav class="space-y-1">
                    <button onclick="showSection('profile-info')" id="nav-profile-info" class="nav-item w-full flex items-center gap-2 px-3 py-2 text-sm font-medium rounded-md bg-zinc-100 text-zinc-900">
                        <i data-lucide="user" class="w-4 h-4"></i>
                        Profile Information
                    </button>
                    <button onclick="showSection('change-password')" id="nav-change-password" class="nav-item w-full flex items-center gap-2 px-3 py-2 text-sm font-medium rounded-md text-zinc-600 hover:bg-zinc-50">
                        <i data-lucide="lock" class="w-4 h-4"></i>
                        Change Password
                    </button>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Profile Information Section -->
            <div id="section-profile-info" class="bg-white rounded-lg border border-zinc-200 p-6">
                <h2 class="text-lg font-semibold text-zinc-900 mb-4">Profile Information</h2>
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">

                    <!-- Avatar Upload -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-zinc-700 mb-2">Profile Avatar</label>
                        <div class="flex items-center gap-4">
                            <div class="h-16 w-16 rounded-full bg-zinc-900 flex items-center justify-center text-xl font-bold text-white overflow-hidden">
                                @if($user->avatar)
                                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                                @else
                                    {{ substr($user->name, 0, 1) }}
                                @endif
                            </div>
                            <div>
                                <input type="file" name="avatar" accept="image/*" id="avatarInput" class="hidden" onchange="previewAvatar(this)">
                                <button type="button" onclick="document.getElementById('avatarInput').click()" class="px-3 py-2 text-sm font-medium bg-zinc-100 text-zinc-700 rounded-md hover:bg-zinc-200">
                                    Change Avatar
                                </button>
                                <p class="text-xs text-zinc-500 mt-1">JPG, PNG or WebP (max 2MB)</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-zinc-700 mb-2">Full Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                class="w-full px-3 py-2 text-sm border border-zinc-300 rounded-md focus:outline-none focus:ring-2 focus:ring-zinc-900">
                            @error('name')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-zinc-700 mb-2">Email Address</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                class="w-full px-3 py-2 text-sm border border-zinc-300 rounded-md focus:outline-none focus:ring-2 focus:ring-zinc-900">
                            @error('email')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="phone" class="block text-sm font-medium text-zinc-700 mb-2">Phone Number</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                            class="w-full px-3 py-2 text-sm border border-zinc-300 rounded-md focus:outline-none focus:ring-2 focus:ring-zinc-900"
                            placeholder="+60 12-345-6789">
                        @error('phone')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="address" class="block text-sm font-medium text-zinc-700 mb-2">Delivery Address</label>
                        <textarea name="address" id="address" rows="3"
                            class="w-full px-3 py-2 text-sm border border-zinc-300 rounded-md focus:outline-none focus:ring-2 focus:ring-zinc-900"
                            placeholder="Enter your full delivery address">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2 text-sm font-medium bg-zinc-900 text-white rounded-md hover:bg-zinc-800 flex items-center gap-2">
                            <i data-lucide="save" class="w-4 h-4"></i>
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Change Password Section -->
            <div id="section-change-password" class="bg-white rounded-lg border border-zinc-200 p-6 hidden">
                <h2 class="text-lg font-semibold text-zinc-900 mb-4">Change Password</h2>
                <form action="{{ route('profile.password') }}" method="POST">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">

                    <div class="mb-4">
                        <label for="current_password" class="block text-sm font-medium text-zinc-700 mb-2">Current Password</label>
                        <input type="password" name="current_password" id="current_password" required
                            class="w-full px-3 py-2 text-sm border border-zinc-300 rounded-md focus:outline-none focus:ring-2 focus:ring-zinc-900">
                        @error('current_password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-zinc-700 mb-2">New Password</label>
                        <input type="password" name="password" id="password" required
                            class="w-full px-3 py-2 text-sm border border-zinc-300 rounded-md focus:outline-none focus:ring-2 focus:ring-zinc-900"
                            minlength="8">
                        @error('password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm font-medium text-zinc-700 mb-2">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full px-3 py-2 text-sm border border-zinc-300 rounded-md focus:outline-none focus:ring-2 focus:ring-zinc-900"
                            minlength="8">
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2 text-sm font-medium bg-zinc-900 text-white rounded-md hover:bg-zinc-800 flex items-center gap-2">
                            <i data-lucide="lock" class="w-4 h-4"></i>
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();

    function showSection(section) {
        // Hide all sections
        document.getElementById('section-profile-info').classList.add('hidden');
        document.getElementById('section-change-password').classList.add('hidden');

        // Reset nav styles
        document.querySelectorAll('.nav-item').forEach(item => {
            item.classList.remove('bg-zinc-100', 'text-zinc-900');
            item.classList.add('text-zinc-600');
        });

        // Show selected section
        document.getElementById('section-' + section).classList.remove('hidden');

        // Highlight nav item
        const navItem = document.getElementById('nav-' + section);
        navItem.classList.add('bg-zinc-100', 'text-zinc-900');
        navItem.classList.remove('text-zinc-600');
    }

    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];

            // Validate file size
            if (file.size > 2 * 1024 * 1024) {
                alert('File size must be less than 2MB');
                input.value = '';
                return;
            }

            // Update preview
            const reader = new FileReader();
            reader.onload = function(e) {
                const avatarDiv = document.querySelector('.h-16.w-16.rounded-full');
                avatarDiv.innerHTML = `<img src="${e.target.result}" alt="Avatar preview" class="h-full w-full object-cover">`;
            };
            reader.readAsDataURL(file);
        }
    }
</script>
</body>
</html>
