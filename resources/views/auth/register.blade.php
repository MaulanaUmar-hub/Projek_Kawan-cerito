<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Nama -->
        <div>
            <x-input-label for="nama" :value="__('Nama Lengkap')" />
            <x-text-input id="nama" class="block mt-1 w-full" type="text" name="nama" :value="old('nama')" required
                autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('nama')" class="mt-2" />
        </div>

        <!-- Asal -->
        <div class="mt-4">
            <x-input-label for="asal" :value="__('Asal Instansi/Sekolah')" />
            <x-text-input id="asal" class="block mt-1 w-full" type="text" name="asal" :value="old('asal')"
                autocomplete="off" />
            <x-input-error :messages="$errors->get('asal')" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- No HP -->
        <div class="mt-4">
            <x-input-label for="no_hp" :value="__('Nomor HP')" />
            <x-text-input id="no_hp" class="block mt-1 w-full" type="text" name="no_hp" :value="old('no_hp')"
                autocomplete="off" />
            <x-input-error :messages="$errors->get('no_hp')" class="mt-2" />
        </div>

        <!-- Gender -->
        <div class="mt-4">
            <x-input-label :value="__('Jenis Kelamin')" />
            <div class="flex gap-4 mt-1">
                <label class="flex items-center gap-2 text-sm text-gray-700">
                    <input type="radio" name="gender" value="L" {{ old('gender') == 'L' ? 'checked' : '' }}
                        required />
                    Laki-laki
                </label>
                <label class="flex items-center gap-2 text-sm text-gray-700">
                    <input type="radio" name="gender" value="P" {{ old('gender') == 'P' ? 'checked' : '' }} />
                    Perempuan
                </label>
            </div>
            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
        </div>

        <!-- Role -->
        <div class="mt-4">
            <x-input-label for="role" :value="__('Daftar Sebagai')" />
            <select id="role" name="role"
                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="konseli" {{ old('role') == 'konseli' ? 'selected' : '' }}>Konseli</option>
                <option value="konselor" {{ old('role') == 'konselor' ? 'selected' : '' }}>Konselor</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                href="{{ route('login') }}">
                {{ __('Sudah punya akun?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Daftar') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
