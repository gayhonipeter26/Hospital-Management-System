<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref, computed } from 'vue'
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale,
} from 'chart.js'
import { Bar } from 'vue-chartjs'

// Register Chart.js components
ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

const props = defineProps({
  stats: Object,
  events: { type: Array, default: () => [] }, // 🔹 Events passed from backend
})

// Chart Data
const chartData = ref({
  labels: ['Users', 'Patients', 'Archived'],
  datasets: [
    {
      label: 'System Stats',
      backgroundColor: ['#3b82f6', '#22c55e', '#ef4444'],
      data: [
        props.stats.total_users,
        props.stats.total_patients,
        props.stats.archived_patients,
      ],
    },
  ],
})

const chartOptions = ref({
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: { display: false },
    title: {
      display: true,
      text: 'System Overview',
    },
  },
})

// Calendar Date
const today = new Date()
const currentMonth = today.toLocaleString('default', { month: 'long' })
const currentYear = today.getFullYear()

// Interactive calendar state
const selectedDay = ref(today.getDate())

const selectDay = (day) => {
  selectedDay.value = day
}

// 🔹 Compute events for the selected day
const selectedDateEvents = computed(() => {
  const selectedDate = `${currentYear}-${String(
    today.getMonth() + 1
  ).padStart(2, '0')}-${String(selectedDay.value).padStart(2, '0')}`

  return props.events.filter((event) => event.date === selectedDate)
})

// 🔹 Helper: check if a day has events
const hasEvents = (day) => {
  const date = `${currentYear}-${String(today.getMonth() + 1).padStart(
    2,
    '0'
  )}-${String(day).padStart(2, '0')}`
  return props.events.some((event) => event.date === date)
}
</script>

<template>
  <AppLayout>
    <div class="p-6 space-y-6">
      <!-- Title -->
      <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
        Admin Dashboard
      </h1>

      <!-- Dashboard Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Stats Cards -->
        <div
          v-for="(value, key) in {
            'Total Users': props.stats.total_users,
            'Total Patients': props.stats.total_patients,
            'Archived Patients': props.stats.archived_patients,
          }"
          :key="key"
          class="p-6 bg-white dark:bg-gray-800 shadow rounded-lg"
        >
          <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400">
            {{ key }}
          </h2>
          <p
            class="mt-2 text-3xl font-bold"
            :class="{
              'text-blue-600 dark:text-blue-400': key === 'Total Users',
              'text-green-600 dark:text-green-400': key === 'Total Patients',
              'text-red-600 dark:text-red-400': key === 'Archived Patients',
            }"
          >
            {{ value }}
          </p>
        </div>

        <!-- Chart -->
        <div
          class="p-6 bg-white dark:bg-gray-800 shadow rounded-lg lg:col-span-2"
        >
          <div class="h-64">
            <Bar :data="chartData" :options="chartOptions" />
          </div>
        </div>

        <!-- Calendar -->
        <div class="p-6 bg-white dark:bg-gray-800 shadow rounded-lg">
          <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
            {{ currentMonth }} {{ currentYear }}
          </h2>
          <div class="grid grid-cols-7 gap-1 mt-4 text-center text-sm">
            <div
              v-for="day in ['Sun','Mon','Tue','Wed','Thu','Fri','Sat']"
              :key="day"
              class="font-medium text-gray-500 dark:text-gray-400"
            >
              {{ day }}
            </div>
            <div
              v-for="n in new Date(today.getFullYear(), today.getMonth(), 1).getDay()"
              :key="'empty-'+n"
            ></div>
            <div
              v-for="d in new Date(today.getFullYear(), today.getMonth()+1, 0).getDate()"
              :key="d"
              class="py-1 rounded cursor-pointer relative"
              :class="{
                'bg-blue-600 text-white': d === selectedDay,
                'hover:bg-gray-200 dark:hover:bg-gray-700': d !== selectedDay
              }"
              @click="selectDay(d)"
            >
              {{ d }}
              <!-- 🔹 Event indicator -->
              <span
                v-if="hasEvents(d)"
                class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-1.5 h-1.5 rounded-full bg-green-500"
              ></span>
            </div>
          </div>

          <!-- Selected day events -->
          <div class="mt-4">
            <p class="text-gray-700 dark:text-gray-300 font-medium">
              Selected: 
              <span class="font-semibold text-blue-600 dark:text-blue-400">
                {{ selectedDay }} {{ currentMonth }} {{ currentYear }}
              </span>
            </p>
            <ul v-if="selectedDateEvents.length" class="mt-2 space-y-2">
              <li
                v-for="event in selectedDateEvents"
                :key="event.id"
                class="p-2 bg-gray-100 dark:bg-gray-700 rounded"
              >
                <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">
                  {{ event.title }}
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                  {{ event.description }}
                </p>
              </li>
            </ul>
            <p v-else class="mt-2 text-sm text-gray-500 dark:text-gray-400">
              No events for this day.
            </p>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
