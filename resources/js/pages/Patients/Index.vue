<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref, onMounted, watch } from 'vue'

// Props from Laravel backend
const props = defineProps({
  patients: Object,
  filters: Object,
})

// Dropdown state
const openDropdown = ref(null)
const toggleDropdown = (id) => {
  openDropdown.value = openDropdown.value === id ? null : id
}

// Close dropdown when clicking outside
onMounted(() => {
  document.addEventListener('click', (e) => {
    if (!e.target.closest('.dropdown-container')) {
      openDropdown.value = null
    }
  })
})

// Unified search field
const search = ref(props.filters?.search || '')

// Debounce function for search
let debounceTimer
watch(search, (value) => {
  clearTimeout(debounceTimer)
  debounceTimer = setTimeout(() => {
    router.get(
      '/patients',
      { search: value },
      { preserveState: true, replace: true }
    )
  }, 400) // delay in ms
})

// Archive action with redirect
const archivePatient = (id) => {
  if (
    confirm(
      '⚠️ Are you sure you want to archive this patient?\n\nThey will be moved to the Archived Patients list.'
    )
  ) {
    router.delete(`/patients/${id}`, {
      onSuccess: () => {
        openDropdown.value = null
        // Redirect to Archived Patients page after success
        router.visit('/patients/archive')
      },
    })
  }
}

// Format Date & Time
const formatDateTime = (datetime) => {
  if (!datetime) return '-'
  const date = new Date(datetime)
  return (
    date.getFullYear() +
    '-' +
    String(date.getMonth() + 1).padStart(2, '0') +
    '-' +
    String(date.getDate()).padStart(2, '0') +
    ' ' +
    String(date.getHours()).padStart(2, '0') +
    ':' +
    String(date.getMinutes()).padStart(2, '0')
  )
}
</script>

<template>
  <AppLayout>
    <div class="p-6 space-y-6">
      <!-- Header -->
      <div class="flex items-center justify-between">
        <h1 class="text-xl font-bold text-gray-800 dark:text-gray-200">
          Patient Master Register
        </h1>
        <div class="flex items-center gap-2">
          <!-- Archives Button -->
          <Link
            href="/patients/archive"
            class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700"
          >
            Archives
          </Link>
          <!-- New Patient Button -->
          <Link
            href="/patients/create"
            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
          >
            + New Patient
          </Link>
        </div>
      </div>

      <!-- Single Search Bar -->
      <div
        class="flex items-center gap-2 bg-white dark:bg-gray-800 p-4 shadow rounded-lg"
      >
        <input
          v-model="search"
          type="text"
          placeholder="Search by Patient No, Name, Phone, or Residence..."
          class="border rounded p-2 w-full dark:bg-gray-700 dark:text-gray-200"
        />
      </div>

      <!-- Patients Table -->
      <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-gray-100 dark:bg-gray-700 border-b text-sm">
              <th class="px-4 py-2">Patient No.</th>
              <th class="px-4 py-2">Patient Name</th>
              <th class="px-4 py-2">Gender</th>
              <th class="px-4 py-2">Age</th>
              <th class="px-4 py-2">Registration Date</th>
              <th class="px-4 py-2">Primary Contact</th>
              <th class="px-4 py-2">Residence</th>
              <th class="px-4 py-2">Patient Status</th>
              <th class="px-4 py-2">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="patient in patients.data"
              :key="patient.id"
              class="border-b hover:bg-gray-50 dark:hover:bg-gray-600"
            >
              <!-- Patient Number (ID) -->
              <td class="px-4 py-2">{{ patient.id }}</td>

              <!-- Patient Name -->
              <td class="px-4 py-2 flex items-center gap-2">
                <img
                  v-if="patient.avatar"
                  :src="patient.avatar"
                  class="w-8 h-8 rounded-full object-cover"
                />
                <div
                  v-else
                  class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center"
                >
                  <span class="text-gray-500">👤</span>
                </div>
                <span>{{ patient.first_name }} {{ patient.last_name }}</span>
              </td>

              <td class="px-4 py-2">{{ patient.gender }}</td>
              <td class="px-4 py-2">
                {{
                  patient.dob
                    ? new Date().getFullYear() -
                        new Date(patient.dob).getFullYear() + ' Years'
                    : '-'
                }}
              </td>
              <td class="px-4 py-2">{{ formatDateTime(patient.created_at) }}</td>

              <!-- ✅ Full phone number -->
              <td class="px-4 py-2">{{ patient.full_phone }}</td>

              <td class="px-4 py-2">{{ patient.residence }}</td>
              <td class="px-4 py-2">
                <span
                  class="px-2 py-1 text-xs rounded bg-green-100 text-green-600"
                >
                  Active
                </span>
              </td>
              <td class="px-4 py-2">
                <div class="relative inline-block text-left dropdown-container">
                  <button
                    @click.stop="toggleDropdown(patient.id)"
                    class="px-3 py-1 bg-gray-200 dark:bg-gray-700 text-sm rounded"
                  >
                    Action ▼
                  </button>

                  <!-- Dropdown -->
                  <transition
                    enter-active-class="transition ease-out duration-100"
                    enter-from-class="transform opacity-0 scale-95"
                    enter-to-class="transform opacity-100 scale-100"
                    leave-active-class="transition ease-in duration-75"
                    leave-from-class="transform opacity-100 scale-100"
                    leave-to-class="transform opacity-0 scale-95"
                  >
                    <div
                      v-if="openDropdown === patient.id"
                      class="absolute mt-2 right-0 w-36 bg-white dark:bg-gray-700 shadow-lg rounded-md z-50"
                    >
                      <Link
                        :href="`/patients/${patient.id}`"
                        class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-600"
                      >
                        View
                      </Link>
                      <Link
                        :href="`/patients/${patient.id}/edit`"
                        class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-600"
                      >
                        Edit
                      </Link>
                      <button
                        @click="archivePatient(patient.id)"
                        class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600"
                      >
                        Archive
                      </button>
                    </div>
                  </transition>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="flex justify-between items-center mt-4">
        <div class="flex space-x-1">
          <template v-for="link in patients.links" :key="link.label">
            <Link
              v-if="link.url"
              :href="link.url"
              v-html="link.label"
              class="px-3 py-1 rounded-lg text-sm"
              :class="{
                'bg-blue-600 text-white': link.active,
                'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300': !link.active,
              }"
            />
            <span
              v-else
              v-html="link.label"
              class="px-3 py-1 text-sm text-gray-400"
            />
          </template>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
