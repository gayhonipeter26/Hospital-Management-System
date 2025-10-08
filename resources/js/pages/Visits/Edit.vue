<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'

// ✅ Props for existing visit data
const props = defineProps<{
  visit: {
    id: number
    start_date: string
    status: string
    patient_type: string
    notes?: string
    department?: string | null
    practitioner_name?: string | null
    patient_id: number
  }
}>()

// ✅ Prefill form with visit data
const form = useForm({
  start_date: props.visit.start_date,
  status: props.visit.status,
  patient_type: props.visit.patient_type,
  department: props.visit.department,
  practitioner_name: props.visit.practitioner_name,
  notes: props.visit.notes,
})

// ✅ Submit form
function submit() {
  form.put(`/visits/${props.visit.id}`)
}
</script>

<template>
  <AppLayout>
    <Head title="Edit Visit" />

    <div class="min-h-screen bg-black text-white px-6 py-10">
      <!-- Header Section -->
      <div class="max-w-4xl mx-auto flex justify-between items-center mb-10">
        <h1 class="text-3xl font-bold tracking-wide">Edit Visit</h1>
        <a
          href="/visits"
          class="bg-gray-800 hover:bg-gray-700 text-white px-4 py-2 rounded-lg border border-gray-700 transition"
        >
          Back
        </a>
      </div>

      <!-- Edit Visit Form -->
      <div class="max-w-4xl mx-auto bg-gray-900 p-8 rounded-xl shadow-lg border border-gray-800">
        <form @submit.prevent="submit" class="space-y-6">
          <!-- Start Date -->
          <div>
            <label class="block text-gray-400 mb-2 font-medium">Start Date</label>
            <input
              v-model="form.start_date"
              type="datetime-local"
              class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white focus:ring-2 focus:ring-red-600 focus:outline-none"
            />
            <p v-if="form.errors.start_date" class="text-red-500 text-sm mt-1">
              {{ form.errors.start_date }}
            </p>
          </div>

          <!-- Status -->
          <div>
            <label class="block text-gray-400 mb-2 font-medium">Status</label>
            <select
              v-model="form.status"
              class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white focus:ring-2 focus:ring-red-600 focus:outline-none"
            >
              <option value="Check In">Check In</option>
              <option value="Pending">Pending</option>
              <option value="Check Out">Check Out</option>
            </select>
            <p v-if="form.errors.status" class="text-red-500 text-sm mt-1">
              {{ form.errors.status }}
            </p>
          </div>

          <!-- Patient Type -->
          <div>
            <label class="block text-gray-400 mb-2 font-medium">Patient Type</label>
            <select
              v-model="form.patient_type"
              class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white focus:ring-2 focus:ring-red-600 focus:outline-none"
            >
              <option value="Inpatient">Inpatient</option>
              <option value="Outpatient">Outpatient</option>
            </select>
            <p v-if="form.errors.patient_type" class="text-red-500 text-sm mt-1">
              {{ form.errors.patient_type }}
            </p>
          </div>

          <!-- Department -->
          <div>
            <label class="block text-gray-400 mb-2 font-medium">Department</label>
            <input
              v-model="form.department"
              type="text"
              class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white focus:ring-2 focus:ring-red-600 focus:outline-none"
            />
            <p v-if="form.errors.department" class="text-red-500 text-sm mt-1">
              {{ form.errors.department }}
            </p>
          </div>

          <!-- Practitioner Name -->
          <div>
            <label class="block text-gray-400 mb-2 font-medium">Practitioner Name</label>
            <input
              v-model="form.practitioner_name"
              type="text"
              class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white focus:ring-2 focus:ring-red-600 focus:outline-none"
            />
            <p v-if="form.errors.practitioner_name" class="text-red-500 text-sm mt-1">
              {{ form.errors.practitioner_name }}
            </p>
          </div>

          <!-- Notes -->
          <div>
            <label class="block text-gray-400 mb-2 font-medium">Notes</label>
            <textarea
              v-model="form.notes"
              rows="4"
              class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white focus:ring-2 focus:ring-red-600 focus:outline-none"
            ></textarea>
            <p v-if="form.errors.notes" class="text-red-500 text-sm mt-1">
              {{ form.errors.notes }}
            </p>
          </div>

          <!-- Submit Button -->
          <div class="flex justify-end">
            <button
              type="submit"
              class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg shadow transition disabled:opacity-50"
              :disabled="form.processing"
            >
              Update Visit
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>
