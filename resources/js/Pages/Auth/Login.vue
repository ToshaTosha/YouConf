<template>
    <div class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
        <h1 class="text-3xl font-bold mb-6">Авторизация</h1>
        <div class="flex space-x-4 mb-6">
            <button 
                @click="showLogin = true" 
                class="px-4 py-2 font-semibold text-white bg-blue-500 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400"
            >
                Вход
            </button>
            <button 
                @click="showLogin = false" 
                class="px-4 py-2 font-semibold text-white bg-blue-500 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400"
            >
                Регистрация
            </button>
            <a href="/auth/vk">Войти через ВКонтакте</a>
        </div>

        <div v-if="showLogin" class="w-full max-w-md p-6 bg-white rounded-lg shadow-md">
            <h2 class="text-2xl font-semibold mb-4">Вход</h2>
            <form @submit.prevent="submitLogin">
                <div class="mb-4">
                    <input 
                        v-model="loginForm.email" 
                        type="email" 
                        placeholder="Email" 
                        required 
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                    />
                </div>
                <div class="mb-4">
                    <input 
                        v-model="loginForm.password" 
                        type="password" 
                        placeholder="Пароль" 
                        required 
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                    />
                </div>
                <button 
                    type="submit" 
                    class="w-full px-4 py-2 font-semibold text-white bg-blue-500 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400"
                >
                    Войти
                </button>
                <div v-if="loginErrors" class="mt-2 text-red-500">{{ loginErrors }}</div>
            </form>
        </div>

        <div v-else class="w-full max-w-md p-6 bg-white rounded-lg shadow-md">
            <h2 class="text-2xl font-semibold mb-4">Регистрация</h2>
            <form @submit.prevent="submitRegister">
                <div class="mb-4">
                    <input 
                        v-model="registerForm.name" 
                        type="text" 
                        placeholder="Имя" 
                        required 
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                    />
                </div>
                <div class="mb-4">
                    <input 
                        v-model="registerForm.email" 
                        type="email" 
                        placeholder="Email" 
                        required 
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                    />
                </div>
                <div class="mb-4">
                    <input 
                        v-model="registerForm.password" 
                        type="password" 
                        placeholder="Пароль" 
                        required 
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                    />
                </div>
                <div class="mb-4">
                    <input 
                        v-model="registerForm.password_confirmation" 
                        type="password" 
                        placeholder="Подтверждение пароля" 
                        required 
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                    />
                </div>
                <button 
                    type="submit" 
                    class="w-full px-4 py-2 font-semibold text-white bg-blue-500 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400"
                >
                    Зарегистрироваться
                </button>
                <div v-if="registerErrors" class="mt-2 text-red-500">{{ registerErrors }}</div>
            </form>
        </div>
    </div>
</template>

<script>
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';

export default {
    setup() {
        const showLogin = ref(true);
        const loginForm = ref({
            email: '',
            password: '',
        });
        const registerForm = ref({
            name: '',
            email: '',
            password: '',
            password_confirmation: '',
        });
        const loginErrors = ref(null);
        const registerErrors = ref(null);

        const submitLogin = () => {
            Inertia.post('/login', loginForm.value, {
                onError: (error) => {
                    loginErrors.value = error;
                },
            });
        };

        const submitRegister = () => {
            Inertia.post('/register', registerForm.value, {
                onError: (error) => {
                    registerErrors.value = error;
                },
            });
        };

        return { 
            showLogin, 
            loginForm, 
            registerForm, 
            loginErrors, 
            registerErrors, 
            submitLogin, 
            submitRegister 
        };
    },
};
</script>
