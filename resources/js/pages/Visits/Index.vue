<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

// Props from controller
const props = defineProps<{
  visits: {
    id: number
    start_date: string
    status: string
    patient_type: string
    notes: string
    department: string | null
    practitioner_name: string | null
    patient: {
      id: number
      first_name: string
      last_name: string
    }
  }[]
}>()

// 🔍 Search query
const searchQuery = ref('')

// 🔎 Filtered visits (computed)
const filteredVisits = computed(() => {
  const q = searchQuery.value.toLowerCase()
  return props.visits.filter((visit) => {
    const patientName = `${visit.patient.first_name} ${visit.patient.last_name}`.toLowerCase()
    return (
      patientName.includes(q) ||
      (visit.department && visit.department.toLowerCase().includes(q)) ||
      (visit.practitioner_name && visit.practitioner_name.toLowerCase().includes(q)) ||
      (visit.notes && visit.notes.toLowerCase().includes(q))
    )
  })
})
</script>

<template>
  <AppLayout>
    <Head title="All Visits" />

    <div class="p-6 space-y-6 bg-black min-h-screen text-white">
      <!-- Header -->
      <div
        class="flex flex-col md:flex-row md:items-center md:justify-between gap-3"
      >
        <h1 class="text-2xl font-bold">All Visits</h1>

        <div class="flex items-center gap-3">
          <!-- 🔍 Search Bar -->
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search by patient, department, or notes..."
            class="bg-gray-800 text-white border border-gray-600 rounded-lg px-3 py-2 w-72 focus:outline-none focus:ring focus:ring-red-500"
          />

          <!-- ➕ Add Visit -->
          <Link
            href="/visits/create"
            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded transition"
          >
            Add Visit
          </Link>
        </div>
      </div>

      <!-- Table -->
      <div
        class="overflow-x-auto border border-white/10 rounded-lg shadow-md bg-gray-900"
      >
        <table class="w-full text-sm text-left">
          <thead class="bg-gray-800 text-gray-300 uppercase text-xs">
            <tr>
              <th class="px-4 py-3">#</th>
              <th class="px-4 py-3">Patient</th>
              <th class="px-4 py-3">Start Date</th>
              <th class="px-4 py-3">Status</th>
              <th class="px-4 py-3">Type</th>
              <th class="px-4 py-3">Department</th>
              <th class="px-4 py-3">Practitioner</th>
              <th class="px-4 py-3">Notes</th>
              <th class="px-4 py-3 text-right">Actions</th>
            </tr>
          </thead>

          <tbody>
            <tr
              v-for="(visit, index) in filteredVisits"
              :key="visit.id"
              class="border-b border-white/10 hover:bg-gray-800 transition"
            >
              <td class="px-4 py-2">{{ index + 1 }}</td>
              <td class="px-4 py-2">
                {{ visit.patient.first_name }} {{ visit.patient.last_name }}
              </td>
              <td class="px-4 py-2">
                {{ new Date(visit.start_date).toLocaleString() }}
              </td>
              <td class="px-4 py-2">{{ visit.status }}</td>
              <td class="px-4 py-2">{{ visit.patient_type }}</td>
              <td class="px-4 py-2">{{ visit.department || '—' }}</td>
              <td class="px-4 py-2">{{ visit.practitioner_name || '—' }}</td>
              <td class="px-4 py-2 truncate max-w-[200px]">
                {{ visit.notes || '—' }}
              </td>

              <td class="px-4 py-2 text-right">
                <Link
                  :href="`/visits/${visit.id}`"
                  class="px-3 py-1 text-sm border border-white/20 rounded hover:bg-gray-700 transition"
                >
                  View
                </Link>
              </td>
            </tr>

            <!-- Empty state -->
            <tr v-if="filteredVisits.length === 0">
              <td colspan="9" class="px-4 py-6 text-center text-gray-400">
                No visits found.
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AppLayout>
</template>
