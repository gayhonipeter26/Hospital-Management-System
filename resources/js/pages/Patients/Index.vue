<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref, onMounted, watch } from 'vue'

const props = defineProps({
  patients: Object,
  filters: Object,
})

const openDropdown = ref(null)
const toggleDropdown = (id) => {
  openDropdown.value = openDropdown.value === id ? null : id
}

onMounted(() => {
  document.addEventListener('click', (e) => {
    if (!e.target.closest('.dropdown-container')) {
      openDropdown.value = null
    }
  })
})

const search = ref(props.filters?.search || '')

let debounceTimer
watch(search, (value) => {
  clearTimeout(debounceTimer)
  debounceTimer = setTimeout(() => {
    router.get(
      '/patients',
      { search: value },
      { preserveState: true, replace: true }
    )
  }, 400)
})

const archivePatient = (id) => {
  if (confirm('⚠️ Archive this patient? They will move to Archives.')) {
    router.delete(`/patients/${id}`, {
      onSuccess: () => {
        openDropdown.value = null
        router.visit('/patients/archive')
      },
    })
  }
}

const toggleStatus = (id, currentStatus) => {
  const newStatus = currentStatus === 'active' ? 'inactive' : 'active'
  const action = newStatus === 'active' ? 'Activate' : 'Deactivate'
  if (confirm(`⚠️ Are you sure you want to ${action} this patient?`)) {
    router.put(`/patients/${id}/status`, { status: newStatus }, {
      onSuccess: () => {
        openDropdown.value = null
      },
    })
  }
}

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
    <div class="p-6 space-y-6 bg-black min-h-screen">
      <!-- Header -->
      <div class="flex items-center justify-between">
        <h1 class="text-xl font-bold text-white">
          Patient Master Register
        </h1>
        <div class="flex items-center gap-2">
          <Link
            href="/patients/archive"
            class="px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-700 border border-white/20 transition-colors"
          >
            Archives
          </Link>
          <Link
            href="/patients/create"
            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition-colors"
          >
            + New Patient
          </Link>
        </div>
      </div>

      <!-- Search -->
      <div class="bg-gray-900 p-4 shadow rounded-lg border border-white/10">
        <input
          v-model="search"
          type="text"
          placeholder="Search by Patient No, Name, Phone, Residence..."
          class="border border-white/20 rounded p-2 w-full bg-gray-800 text-white placeholder:text-gray-500 focus:border-red-600 focus:ring-red-600 focus:outline-none"
        />
      </div>

      <!-- Table (Desktop) -->
      <div class="hidden md:block bg-gray-900 shadow rounded-lg border border-white/10">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-gray-800 border-b border-white/10 text-sm text-white">
              <th class="px-4 py-2">Patient No.</th>
              <th class="px-4 py-2">Patient Name</th>
              <th class="px-4 py-2">Gender</th>
              <th class="px-4 py-2">Age</th>
              <th class="px-4 py-2">Registration Date</th>
              <th class="px-4 py-2">Primary Contact</th>
              <th class="px-4 py-2">Residence</th>
              <th class="px-4 py-2">Status</th>
              <th class="px-4 py-2 text-right">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="patient in patients.data"
              :key="patient.id"
              class="border-b border-white/10 hover:bg-gray-800 transition-colors text-gray-300"
            >
              <td class="px-4 py-2">{{ patient.id }}</td>
              <td class="px-4 py-2 flex items-center gap-2">
                <img
                  v-if="patient.avatar"
                  :src="patient.avatar"
                  class="w-8 h-8 rounded-full object-cover"
                />
                <div
                  v-else
                  class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center"
                >
                  <span class="text-gray-400">👤</span>
                </div>
                <span class="text-white">{{ patient.first_name }} {{ patient.last_name }}</span>
              </td>
              <td class="px-4 py-2">{{ patient.gender }}</td>
              <td class="px-4 py-2">
                {{
                  patient.dob
                    ? (() => {
                        const dob = new Date(patient.dob)
                        const today = new Date()
                        let years = today.getFullYear() - dob.getFullYear()
                        let months = today.getMonth() - dob.getMonth()
                        let days = today.getDate() - dob.getDate()

                        if (days < 0) {
                          months--
                          days += new Date(today.getFullYear(), today.getMonth(), 0).getDate()
                        }
                        if (months < 0) {
                          years--
                          months += 12
                        }

                        return `${years} Years ${months} Months ${days} Days`
                      })()
                    : '-'
                }}
              </td>
              <td class="px-4 py-2">{{ formatDateTime(patient.created_at) }}</td>
              <td class="px-4 py-2">{{ patient.full_phone }}</td>
              <td class="px-4 py-2">{{ patient.residence }}</td>

              <!-- ✅ Status -->
              <td class="px-4 py-2">
                <span
                  v-if="patient.status?.toLowerCase() === 'active'"
                  class="px-2 py-1 text-xs rounded bg-green-900/50 text-green-400 border border-green-700"
                >
                  Active
                </span>
                <span
                  v-else
                  class="px-2 py-1 text-xs rounded bg-red-900/50 text-red-400 border border-red-700"
                >
                  Inactive
                </span>
              </td>

              <!-- ✅ Dropdown -->
              <td class="px-4 py-2 w-44 text-right relative">
                <div class="inline-block text-left dropdown-container">
                  <button
                    @click.stop="toggleDropdown(patient.id)"
                    class="px-3 py-1 bg-gray-800 text-white text-sm rounded border border-white/20 hover:border-red-600 transition-colors"
                  >
                    Action ▼
                  </button>
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
                      class="absolute right-0 mt-2 w-44 bg-gray-800 border border-white/20 shadow-lg rounded-md z-50"
                    >
                      <Link
                        :href="`/patients/${patient.id}`"
                        class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 transition-colors"
                      >
                        👁 View
                      </Link>
                      <Link
                        :href="`/patients/${patient.id}/edit`"
                        class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 transition-colors"
                      >
                        ✏️ Edit
                      </Link>
                      <button
                        @click="archivePatient(patient.id)"
                        class="block w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-gray-700 transition-colors"
                      >
                        🗑 Archive
                      </button>

                      <!-- ✅ Activate / Deactivate -->
                      <button
                        v-if="patient.status?.toLowerCase() === 'active'"
                        @click="toggleStatus(patient.id, 'active')"
                        class="block w-full text-left px-4 py-2 text-sm text-yellow-400 hover:bg-gray-700 transition-colors"
                      >
                        ⛔ Deactivate
                      </button>
                      <button
                        v-else
                        @click="toggleStatus(patient.id, 'inactive')"
                        class="block w-full text-left px-4 py-2 text-sm text-green-400 hover:bg-gray-700 transition-colors"
                      >
                        ✅ Activate
                      </button>
                    </div>
                  </transition>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Mobile Card View -->
      <div class="block md:hidden space-y-4">
        <div
          v-for="patient in patients.data"
          :key="patient.id"
          class="bg-gray-900 p-4 rounded-lg shadow border border-white/10"
        >
          <div class="flex items-center gap-3 mb-3">
            <img
              v-if="patient.avatar"
              :src="patient.avatar"
              class="w-10 h-10 rounded-full object-cover"
            />
            <div
              v-else
              class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center"
            >
              <span class="text-gray-400">👤</span>
            </div>
            <div>
              <h2 class="font-semibold text-white">
                {{ patient.first_name }} {{ patient.last_name }}
              </h2>
              <p class="text-sm text-gray-400">
                {{ patient.gender }} • {{
                  patient.dob
                    ? (() => {
                        const dob = new Date(patient.dob)
                        const today = new Date()
                        let years = today.getFullYear() - dob.getFullYear()
                        let months = today.getMonth() - dob.getMonth()
                        let days = today.getDate() - dob.getDate()

                        if (days < 0) {
                          months--
                          days += new Date(today.getFullYear(), today.getMonth(), 0).getDate()
                        }
                        if (months < 0) {
                          years--
                          months += 12
                        }

                        return `${years} Years ${months} Months ${days} Days`
                      })()
                    : '-'
                }}
              </p>
            </div>
          </div>
          <p class="text-sm text-gray-300"><strong class="text-white">Patient No:</strong> {{ patient.id }}</p>
          <p class="text-sm text-gray-300"><strong class="text-white">Phone:</strong> {{ patient.full_phone }}</p>
          <p class="text-sm text-gray-300"><strong class="text-white">Residence:</strong> {{ patient.residence }}</p>
          <p class="text-sm text-gray-300"><strong class="text-white">Status:</strong>
            <span
              v-if="patient.status?.toLowerCase() === 'active'"
              class="px-2 py-0.5 text-xs rounded bg-green-900/50 text-green-400 border border-green-700"
            >
              Active
            </span>
            <span
              v-else
              class="px-2 py-0.5 text-xs rounded bg-red-900/50 text-red-400 border border-red-700"
            >
              Inactive
            </span>
          </p>
          <div class="flex gap-2 mt-3">
            <Link
              :href="`/patients/${patient.id}`"
              class="px-3 py-1 bg-gray-800 text-white text-sm rounded border border-white/20 hover:border-red-600 transition-colors"
            >
              👁 View
            </Link>
            <Link
              :href="`/patients/${patient.id}/edit`"
              class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700 transition-colors"
            >
              ✏️ Edit
            </Link>
          </div>
        </div>
      </div>

      <!-- Pagination + Counter -->
      <div class="flex flex-col md:flex-row md:justify-between md:items-center mt-4 gap-2">
        <div class="text-sm text-gray-400">
          Showing
          <span class="font-semibold text-white">{{ patients.from }}</span> –
          <span class="font-semibold text-white">{{ patients.to }}</span>
          of
          <span class="font-semibold text-white">{{ patients.total }}</span> patients
        </div>

        <div class="flex space-x-1">
          <template v-for="link in patients.links" :key="link.label">
            <Link
              v-if="link.url"
              :href="link.url"
              v-html="link.label"
              preserve-scroll
              preserve-state
              class="px-3 py-1 rounded-lg text-sm transition-colors"
              :class="{
                'bg-red-600 text-white hover:bg-red-700': link.active,
                'bg-gray-800 text-gray-300 border border-white/20 hover:border-red-600': !link.active,
              }"
            />
            <span
              v-else
              v-html="link.label"
              class="px-3 py-1 text-sm text-gray-600"
            />
          </template>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
td {
  position: relative;
}
.w-44 {
  min-width: 11rem;
}
</style>
