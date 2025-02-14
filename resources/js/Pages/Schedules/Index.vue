<template>
  <div class="p-4">
    <table class="min-w-full bg-white border border-gray-200">
      <thead>
        <tr class="bg-gray-100">
          <th class="py-2 px-4 border-b text-left text-gray-600">Дата</th>
          <th class="py-2 px-4 border-b text-left text-gray-600">Время</th>
          <th class="py-2 px-4 border-b text-left text-gray-600">Место</th>
          <th class="py-2 px-4 border-b text-left text-gray-600">Заявка</th>
          <th class="py-2 px-4 border-b text-left text-gray-600">Категория</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(events, date) in schedule" :key="date" class="border-b">
          <td class="py-2 px-4 font-semibold">{{ date }}</td>
          <td colspan="4">
            <table class="min-w-full">
              <tbody>
                <tr
                  v-for="event in events"
                  :key="event.id"
                  class="hover:bg-gray-50"
                >
                  <td class="py-2 px-4 border-b">
                    {{
                      new Date(event.scheduled_at).toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit',
                      })
                    }}
                  </td>
                  <td class="py-2 px-4 border-b">{{ event.location }}</td>
                  <td class="py-2 px-4 border-b">
                    {{ event.application.name }}
                  </td>
                  <td class="py-2 px-4 border-b">
                    {{ event.application.category }}
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  props: {
    schedule: Object,
  },
}
</script>
