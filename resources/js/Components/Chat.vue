<template>
  <div class="mt-8">
    <h2 class="text-lg font-semibold mb-4">Чат по заявке</h2>
    <div class="bg-white shadow-md rounded-lg p-6">
      <!-- Окно сообщений -->
      <div class="overflow-y-auto max-h-64 mb-4">
        <div v-for="message in messages" :key="message.id" class="mb-4">
          <!-- Сообщение участника -->
          <div
            v-if="message.user.id === application.user_id"
            class="flex justify-end"
          >
            <div class="bg-blue-500 text-white rounded-lg p-3 max-w-md">
              <p class="text-sm">{{ message.message }}</p>
              <span class="text-xs text-blue-200">
                {{ message.user.first_name }}
              </span>
            </div>
          </div>

          <!-- Сообщение эксперта -->
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

      <!-- Поле ввода сообщения -->
      <input
        v-model="newMessage"
        @keyup.enter="sendMessage"
        placeholder="Введите сообщение..."
        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
      />
    </div>
  </div>
</template>

<script>
export default {
  props: {
    chat: Object,
    messages: Array,
    application: Object,
  },
  data() {
    return {
      newMessage: '',
    }
  },
  methods: {
    sendMessage() {
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
