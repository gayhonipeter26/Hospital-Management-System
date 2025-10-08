<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  patient: Object,
  visits: Object, // paginated list
})
</script>

<template>
  <div class="p-6">
    <h1 class="text-xl font-bold mb-4">
      Visits for {{ patient.first_name }} {{ patient.last_name }}
    </h1>

    <div v-if="visits.data.length === 0">
      <p class="text-gray-500">No visits recorded for this patient.</p>
    </div>

    <table v-else class="min-w-full border border-gray-300">
      <thead>
        <tr class="bg-gray-100">
          <th class="p-2 border">Date</th>
          <th class="p-2 border">Reason</th>
          <th class="p-2 border">Notes</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="visit in visits.data" :key="visit.id">
          <td class="p-2 border">{{ visit.visit_date }}</td>
          <td class="p-2 border">{{ visit.reason }}</td>
          <td class="p-2 border">{{ visit.notes ?? '-' }}</td>
        </tr>
      </tbody>
    </table>

    <!-- Pagination -->
    <div class="mt-4 flex justify-center">
      <Pagination :links="visits.links" />
    </div>
  </div>
</template>
