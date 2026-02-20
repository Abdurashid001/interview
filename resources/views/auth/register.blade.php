<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Role -->
        <div class="mt-4">
            <x-input-label for="role" :value="__('Kim sifatida ro\'yxatdan o\'tasiz?')" />
            <div class="mt-2 space-y-2">
                <div class="flex items-center">
                    <input type="radio" name="role" id="role_student" value="student" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" checked>
                    <label for="role_student" class="ml-2 block text-sm text-gray-700">
                        Student
                    </label>
                </div>
                <div class="flex items-center">
                    <input type="radio" name="role" id="role_teacher" value="teacher" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                    <label for="role_teacher" class="ml-2 block text-sm text-gray-700">
                        O'qituvchi
                    </label>
                </div>
            </div>
            @error('role')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4" id="register-button">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    <!-- O'QITUVCHI BO'LISH UCHUN MAXSUS LINK -->
    <div class="mt-6 pt-4 border-t border-gray-200 text-center">
        <p class="text-sm text-gray-600">
            O'qituvchi bo'lish uchun maxsus ro'yxatdan o'tish kerak.
        </p>
        <a href="{{ route('teacher.register') }}" class="mt-2 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            <i class="fas fa-chalkboard-teacher mr-2"></i> O'qituvchi bo'lish
        </a>
    </div>
</x-guest-layout>

<!-- JAVASCRIPT: Radio button o'zgarganda redirect -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const teacherRadio = document.getElementById('role_teacher');
    const studentRadio = document.getElementById('role_student');
    const registerForm = document.querySelector('form');
    const registerButton = document.getElementById('register-button');

    if (teacherRadio) {
        teacherRadio.addEventListener('change', function() {
            if (this.checked) {
                // Foydalanuvchiga xabar ko'rsatish
                if (confirm('O\'qituvchi sifatida ro\'yxatdan o\'tish uchun alohida forma to\'ldirishingiz kerak. Maxsus formaga o\'tishni xohlaysizmi?')) {
                    window.location.href = "{{ route('teacher.register') }}";
                } else {
                    // Agar rad etsa, Student ni tanlab qo'yish
                    studentRadio.checked = true;
                }
            }
        });
    }

    // Formani yuborishdan oldin tekshirish
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            if (teacherRadio && teacherRadio.checked) {
                e.preventDefault();
                window.location.href = "{{ route('teacher.register') }}";
            }
        });
    }
});
</script>