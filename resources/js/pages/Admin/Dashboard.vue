<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref, computed } from 'vue'

const props = defineProps({
  stats: Object,
  events: { type: Array, default: () => [] },
})

// Calendar Date
const today = new Date()
const currentMonth = ref(today.getMonth())
const currentYear = ref(today.getFullYear())
const selectedDay = ref(today.getDate())

const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
  'July', 'August', 'September', 'October', 'November', 'December']

const daysInMonth = computed(() => 
  new Date(currentYear.value, currentMonth.value + 1, 0).getDate()
)

const firstDayOfMonth = computed(() => 
  new Date(currentYear.value, currentMonth.value, 1).getDay()
)

const monthName = computed(() => monthNames[currentMonth.value])

const selectDay = (day) => {
  selectedDay.value = day
}

const changeMonth = (direction) => {
  if (direction === 'prev') {
    if (currentMonth.value === 0) {
      currentMonth.value = 11
      currentYear.value--
    } else {
      currentMonth.value--
    }
  } else {
    if (currentMonth.value === 11) {
      currentMonth.value = 0
      currentYear.value++
    } else {
      currentMonth.value++
    }
  }
}

// Compute events for the selected day
const selectedDateEvents = computed(() => {
  const selectedDate = `${currentYear.value}-${String(
    currentMonth.value + 1
  ).padStart(2, '0')}-${String(selectedDay.value).padStart(2, '0')}`

  return props.events.filter((event) => event.date === selectedDate)
})

// Helper: check if a day has events
const hasEvents = (day) => {
  const date = `${currentYear.value}-${String(currentMonth.value + 1).padStart(
    2,
    '0'
  )}-${String(day).padStart(2, '0')}`
  return props.events.some((event) => event.date === date)
}

// Max value for bar chart scaling
const maxValue = computed(() => 
  Math.max(props.stats.total_users, props.stats.total_patients, props.stats.archived_patients)
)
</script>

<template>
  <AppLayout>
    <div class="min-h-screen bg-black text-white p-6">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold mb-1">
          Admin <span class="text-red-600">Dashboard</span>
        </h1>
        <p class="text-gray-400">System overview and analytics</p>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Users -->
        <div class="group p-6 bg-gradient-to-br from-gray-900 to-black border border-white/10 rounded-xl hover:border-blue-600/50 transition-all duration-300">
          <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-blue-600/20 rounded-lg flex items-center justify-center group-hover:bg-blue-600/30 transition-colors">
              <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
              </svg>
            </div>
            <span class="text-4xl font-bold text-blue-500">{{ props.stats.total_users }}</span>
          </div>
          <h3 class="text-sm font-medium text-gray-400">Total Users</h3>
        </div>

        <!-- Total Patients -->
        <div class="group p-6 bg-gradient-to-br from-gray-900 to-black border border-white/10 rounded-xl hover:border-green-600/50 transition-all duration-300">
          <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-green-600/20 rounded-lg flex items-center justify-center group-hover:bg-green-600/30 transition-colors">
              <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
            </div>
            <span class="text-4xl font-bold text-green-500">{{ props.stats.total_patients }}</span>
          </div>
          <h3 class="text-sm font-medium text-gray-400">Total Patients</h3>
        </div>

        <!-- Archived Patients -->
        <div class="group p-6 bg-gradient-to-br from-gray-900 to-black border border-white/10 rounded-xl hover:border-red-600/50 transition-all duration-300">
          <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-red-600/20 rounded-lg flex items-center justify-center group-hover:bg-red-600/30 transition-colors">
              <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
              </svg>
            </div>
            <span class="text-4xl font-bold text-red-500">{{ props.stats.archived_patients }}</span>
          </div>
          <h3 class="text-sm font-medium text-gray-400">Archived Patients</h3>
        </div>
      </div>

      <!-- Chart and Calendar Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chart -->
        <div class="lg:col-span-2 p-6 bg-gradient-to-br from-gray-900 to-black border border-white/10 rounded-xl">
          <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-red-600/20 rounded-lg flex items-center justify-center">
              <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
              </svg>
            </div>
            <h2 class="text-xl font-bold">System Overview</h2>
          </div>
          
          <div class="space-y-6">
            <!-- Users Bar -->
            <div>
              <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-gray-400">Users</span>
                <span class="text-sm font-semibold text-blue-500">{{ props.stats.total_users }}</span>
              </div>
              <div class="h-8 bg-white/5 rounded-lg overflow-hidden">
                <div 
                  class="h-full bg-gradient-to-r from-blue-600 to-blue-500 rounded-lg transition-all duration-500"
                  :style="{ width: `${(props.stats.total_users / maxValue) * 100}%` }"
                ></div>
              </div>
            </div>

            <!-- Patients Bar -->
            <div>
              <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-gray-400">Patients</span>
                <span class="text-sm font-semibold text-green-500">{{ props.stats.total_patients }}</span>
              </div>
              <div class="h-8 bg-white/5 rounded-lg overflow-hidden">
                <div 
                  class="h-full bg-gradient-to-r from-green-600 to-green-500 rounded-lg transition-all duration-500"
                  :style="{ width: `${(props.stats.total_patients / maxValue) * 100}%` }"
                ></div>
              </div>
            </div>

            <!-- Archived Bar -->
            <div>
              <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-gray-400">Archived</span>
                <span class="text-sm font-semibold text-red-500">{{ props.stats.archived_patients }}</span>
              </div>
              <div class="h-8 bg-white/5 rounded-lg overflow-hidden">
                <div 
                  class="h-full bg-gradient-to-r from-red-600 to-red-500 rounded-lg transition-all duration-500"
                  :style="{ width: `${(props.stats.archived_patients / maxValue) * 100}%` }"
                ></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Calendar -->
        <div class="p-6 bg-gradient-to-br from-gray-900 to-black border border-white/10 rounded-xl">
          <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 bg-red-600/20 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
              </div>
              <div>
                <h2 class="text-lg font-bold">{{ monthName }}</h2>
                <p class="text-sm text-gray-400">{{ currentYear }}</p>
              </div>
            </div>
            <div class="flex gap-2">
              <button 
                @click="changeMonth('prev')"
                class="w-8 h-8 bg-white/5 rounded-lg flex items-center justify-center hover:bg-white/10 transition-colors"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
              </button>
              <button 
                @click="changeMonth('next')"
                class="w-8 h-8 bg-white/5 rounded-lg flex items-center justify-center hover:bg-white/10 transition-colors"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </button>
            </div>
          </div>

          <div class="grid grid-cols-7 gap-1 mb-4">
            <div
              v-for="day in ['S', 'M', 'T', 'W', 'T', 'F', 'S']"
              :key="day"
              class="text-center text-xs font-medium text-gray-500 py-2"
            >
              {{ day }}
            </div>
          </div>

          <div class="grid grid-cols-7 gap-1">
            <div
              v-for="n in firstDayOfMonth"
              :key="'empty-' + n"
            ></div>
            <button
              v-for="d in daysInMonth"
              :key="d"
              class="relative py-2 text-sm rounded-lg transition-all"
              :class="{
                'bg-red-600 text-white font-bold': d === selectedDay,
                'hover:bg-white/10 text-gray-300': d !== selectedDay
              }"
              @click="selectDay(d)"
            >
              {{ d }}
              <span
                v-if="hasEvents(d)"
                class="absolute bottom-1 left-1/2 transform -translate-x-1/2 w-1 h-1 rounded-full bg-green-500"
              ></span>
            </button>
          </div>

          <!-- Selected Day Events -->
          <div class="mt-6 pt-6 border-t border-white/10">
            <p class="text-sm text-gray-400 mb-3">
              <span class="font-semibold text-red-500">
                {{ selectedDay }} {{ monthName }} {{ currentYear }}
              </span>
            </p>
            <div v-if="selectedDateEvents.length" class="space-y-2">
              <div
                v-for="event in selectedDateEvents"
                :key="event.id"
                class="p-3 bg-white/5 rounded-lg border border-white/10"
              >
                <p class="text-sm font-semibold text-white mb-1">{{ event.title }}</p>
                <p class="text-xs text-gray-400">{{ event.description }}</p>
              </div>
            </div>
            <p v-else class="text-sm text-gray-500">No events scheduled</p>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>