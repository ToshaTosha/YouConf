<template>
  <div class="mt-8">
    <h2 class="text-lg font-semibold mb-4">Чат по заявке</h2>
    <div class="bg-white shadow-md rounded-lg p-6">
      <!-- Окно сообщений -->
      <div class="overflow-y-auto max-h-64 mb-4">
        <!-- Блок-заглушка, если сообщений нет -->
        <div v-if="messages.length === 0" class="text-center text-gray-500">
          В чате пока нет сообщений.
        </div>

        <!-- Список сообщений -->
        <div v-for="message in messages" :key="message.id" class="mb-4">
          <!-- Сообщение текущего пользователя -->
          <div
            v-if="message.user.id === $page.props.user_data.id"
            class="flex justify-end"
          >
            <div class="bg-blue-500 text-white rounded-lg p-3 max-w-md">
              <p class="text-sm">{{ message.message }}</p>
              <span class="text-xs text-blue-200">
                {{ message.user.first_name }}
              </span>
            </div>
          </div>

          <!-- Сообщение собеседника -->
          <div v-else class="flex justify-start">
            <div class="bg-gray-200 text-gray-800 rounded-lg p-3 max-w-md">
              <p class="text-sm">{{ message.message }}</p>
              <span class="text-xs text-gray-500">
                {{ message.user.first_name }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Сообщение о завершении чата -->
      <div v-if="!isActive" class="text-center text-gray-500 mb-4">
        Этот чат завершён.
      </div>

      <!-- Поле ввода сообщения и кнопка отправки -->
      <div v-if="isActive" class="flex items-center gap-2">
        <input
          v-model="newMessage"
          @keyup.enter="sendMessage"
          placeholder="Введите сообщение..."
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
        <!-- Кнопка отправки -->
        <button
          @click="sendMessage"
          class="p-2 text-blue-500 hover:text-blue-600 focus:outline-none"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"
            />
          </svg>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Chat',
  props: {
    chat: Object,
    messages: Array,
    application: Object,
    isActive: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      newMessage: '',
    }
  },
  methods: {
    sendMessage() {
      if (this.newMessage.trim() === '') return

      this.$inertia.post(
        `/chats/${this.chat.id}/messages`,
        {
          message: this.newMessage,
        },
        {
          preserveScroll: true,
          onSuccess: () => {
            this.newMessage = ''
          },
        },
      )
    },
  },
}
</script>
