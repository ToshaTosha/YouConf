<template>
  <div class="max-w-md w-full space-y-8">
    <div>
      <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
        Создать аккаунт
      </h2>
    </div>
    <form class="mt-8 space-y-6" @submit.prevent="submit">
      <div class="rounded-md shadow-sm space-y-4">
        <div>
          <label
            for="first_name"
            class="block text-sm font-medium text-gray-700"
          >
            Имя
          </label>
          <input
            id="first_name"
            v-model="form.first_name"
            type="text"
            required
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
          />
          <p v-if="errors.first_name" class="mt-2 text-sm text-red-600">
            {{ errors.first_name }}
          </p>
        </div>

        <div>
          <label
            for="last_name"
            class="block text-sm font-medium text-gray-700"
          >
            Фамилия
          </label>
          <input
            id="last_name"
            v-model="form.last_name"
            type="text"
            required
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
          />
          <p v-if="errors.last_name" class="mt-2 text-sm text-red-600">
            {{ errors.last_name }}
          </p>
        </div>

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
          />
          <p v-if="errors.email" class="mt-2 text-sm text-red-600">
            {{ errors.email }}
          </p>
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-gray-700">
            Пароль
          </label>
          <input
            id="password"
            v-model="form.password"
            type="password"
            required
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
          />
          <p v-if="errors.password" class="mt-2 text-sm text-red-600">
            {{ errors.password[0] }}
          </p>
        </div>

        <div>
          <label
            for="password_confirmation"
            class="block text-sm font-medium text-gray-700"
          >
            Подтвердите пароль
          </label>
          <input
            id="password_confirmation"
            v-model="form.password_confirmation"
            type="password"
            required
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
          />
        </div>
      </div>

      <div>
        <button
          type="submit"
          class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
          :disabled="processing"
        >
          <span v-if="processing">Регистрация...</span>
          <span v-else>Зарегистрироваться</span>
        </button>
      </div>

      <div class="text-center">
        <router-link
          to="/login"
          class="text-sm text-blue-600 hover:text-blue-500"
        >
          Уже есть аккаунт? Войдите
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
</template>

<script>
import { useForm } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'
import VkAuthButton from '@/Components/VkAuthButton.vue'
import AuthLayout from '@/Common/AuthLayout.vue'

export default {
  setup() {
    const form = useForm({
      first_name: '',
      last_name: '',
      email: '',
      password: '',
      password_confirmation: '',
    })

    const submit = () => {
      Inertia.post('/register', form, {
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
  props: {
    errors: Object,
    message: String,
    status: String,
    user_data: Object,
  },
  components: {
    VkAuthButton,
  },
  meta: {
    layout: AuthLayout,
  },
  computed: {
    processing() {
      return this.form.processing
    },
    errors() {
      console.log(this.form)
      return this.form.errors
    },
  },
}
</script>
