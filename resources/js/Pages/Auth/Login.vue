<template>
  <div
    class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8"
  >
    <div class="max-w-md w-full space-y-8">
      <div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
          Вход в аккаунт
        </h2>
        <p
          v-if="message"
          class="mt-2 text-center text-sm"
          :class="{
            'text-green-600': status === 'success',
            'text-red-600': status === 'error',
          }"
        >
          {{ message }}
        </p>
      </div>
      <form class="mt-8 space-y-6" @submit.prevent="submit">
        <div class="rounded-md shadow-sm space-y-4">
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">
              Email
            </label>
            <input
              id="email"
              v-model="form.email"
              type="email"
              required
              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
              :class="{ 'border-red-500': errors.email }"
            />
            <p v-if="errors.email" class="mt-2 text-sm text-red-600">
              {{ errors.email }}
            </p>
          </div>

          <div>
            <label
              for="password"
              class="block text-sm font-medium text-gray-700"
            >
              Пароль
            </label>
            <input
              id="password"
              v-model="form.password"
              type="password"
              required
              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
              :class="{ 'border-red-500': errors.password }"
            />
            <p v-if="errors.password" class="mt-2 text-sm text-red-600">
              {{ errors.password }}
            </p>
          </div>

          <div class="flex items-center">
            <input
              id="remember"
              v-model="form.remember"
              type="checkbox"
              class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
            />
            <label for="remember" class="ml-2 block text-sm text-gray-900">
              Запомнить меня
            </label>
          </div>
        </div>

        <div>
          <button
            type="submit"
            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            :disabled="form.processing"
          >
            <span v-if="form.processing">Вход...</span>
            <span v-else>Войти</span>
          </button>
        </div>

        <div class="text-center">
          <router-link
            to="/register"
            class="text-sm text-blue-600 hover:text-blue-500"
          >
            Нет аккаунта? Зарегистрируйтесь
          </router-link>
        </div>
        <div class="mt-6">
          <div class="relative">
            <div class="absolute inset-0 flex items-center">
              <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
              <span class="px-2 bg-gray-50 text-gray-500">
                Или войдите через
              </span>
            </div>
          </div>

          <div class="mt-6">
            <VkAuthButton />
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { useForm } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'
import VkAuthButton from '@/Components/VkAuthButton.vue'

export default {
  props: {
    errors: Object,
    message: String,
    status: String,
    user_data: Object,
  },
  components: {
    VkAuthButton,
  },
  setup(props) {
    const form = useForm({
      email: '',
      password: '',
      remember: props.user_data?.remember || false,
    })

    const submit = () => {
      Inertia.post('/login', form, {
        onSuccess: (page) => {
          console.log('page')
          // Если есть ошибки, они будут автоматически обновлены в form.errors
          // Если нет ошибок, вы можете сбросить форму или выполнить другие действия
          if (!page.props.errors) {
            form.reset() // Сброс формы, если нет ошибок
          }
        },
        onError: (errors) => {
          // Ошибки будут автоматически обновлены в form.errors
          console.log(errors) // Вывод ошибок в консоль для отладки
          form.setError(errors)
        },
        //onFinish: () => form.reset('password', 'password_confirmation'),
      })
    }

    return { form, submit }
  },
  computed: {
    processing() {
      return this.form.processing
    },
  },
}
</script>
