<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue'
import { Head, Link } from '@inertiajs/vue3'

defineProps<{
  visit: {
    id: number
    start_date: string
    status: string
    patient_type: string
    notes?: string
    department?: string | null
    practitioner_name?: string | null
    patient: {
      id: number
      first_name: string
      last_name: string
    }
  }
}>()
</script>

<template>
  <AppLayout>
    <Head title="Visit Details" />

    <div class="min-h-screen bg-black text-white px-6 py-10">
      <!-- Page Header -->
      <div class="flex justify-between items-center mb-8 border-b border-gray-700 pb-4">
        <h1 class="text-3xl font-bold">Visit Details</h1>

        <Link
          href="/visits"
          class="bg-gray-800 hover:bg-gray-700 text-white px-4 py-2 rounded-lg border border-gray-600 transition"
        >
          Back
        </Link>
      </div>

      <!-- Visit Info Card -->
      <div class="bg-gray-900 rounded-xl shadow-md border border-gray-800 p-8 space-y-6">
        <!-- Patient Info Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <p class="text-gray-400 text-sm uppercase">Patient</p>
            <p class="text-lg font-semibold">
              {{ visit.patient.first_name }} {{ visit.patient.last_name }}
            </p>
          </div>

          <div>
            <p class="text-gray-400 text-sm uppercase">Status</p>
            <span
              class="inline-block px-3 py-1 text-sm rounded-full mt-1"
              :class="{
                'bg-green-700 text-white': visit.status === 'Check In',
                'bg-yellow-700 text-white': visit.status === 'Pending',
                'bg-red-700 text-white': visit.status === 'Check Out'
              }"
            >
              {{ visit.status }}
            </span>
          </div>

          <div>
            <p class="text-gray-400 text-sm uppercase">Start Date</p>
            <p class="text-lg font-medium">
              {{ new Date(visit.start_date).toLocaleString() }}
            </p>
          </div>

          <div>
            <p class="text-gray-400 text-sm uppercase">Patient Type</p>
            <p class="text-lg font-medium">{{ visit.patient_type }}</p>
          </div>

          <div>
            <p class="text-gray-400 text-sm uppercase">Department</p>
            <p class="text-lg font-medium">{{ visit.department || '—' }}</p>
          </div>

          <div>
            <p class="text-gray-400 text-sm uppercase">Practitioner</p>
            <p class="text-lg font-medium">{{ visit.practitioner_name || '—' }}</p>
          </div>
        </div>

        <!-- Notes Section -->
        <div class="pt-6 border-t border-gray-700">
          <p class="text-gray-400 text-sm uppercase mb-2">Notes</p>
          <p class="text-base leading-relaxed">{{ visit.notes || 'No notes provided.' }}</p>
        </div>
      </div>

      <!-- Buttons Section -->
      <div class="mt-8 flex flex-wrap gap-4">
        <Link
          :href="`/visits/${visit.id}/edit`"
          class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg transition"
        >
          Edit Visit
        </Link>

        <Link
          href="/visits"
          class="px-5 py-2.5 border border-gray-600 text-white rounded-lg hover:bg-gray-800 transition"
        >
          Back to All Visits
        </Link>
      </div>
    </div>
  </AppLayout>
</template>
