<template>
  <div class="tabs">
    <button
      v-for="(date, index) in dateKeys"
      :key="index"
      @click="selectDate(date)"
      :class="{ active: selectedDate === date }"
    >
      {{ date }}
    </button>
  </div>
</template>

<script>
export default {
  props: {
    dateKeys: Array,
    selectedDate: String,
  },
  methods: {
    selectDate(date) {
      this.$emit('date-selected', date) // Эмитируем событие с выбранной датой
    },
  },
}
</script>

<style>
.tabs {
  display: flex;
  margin-bottom: 10px;
}

.tabs button {
  margin-right: 5px;
  padding: 5px 10px;
  cursor: pointer;
}

.tabs button.active {
  background-color: #007bff;
  color: white;
}
</style>
