<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'

// Props from backend
const props = defineProps({
    patients: Object, // Archived patients (soft deleted)
})

const openDropdown = ref(null)
const toggleDropdown = (id) => {
    openDropdown.value = openDropdown.value === id ? null : id
}
const restorePatient = (id) => {
  if (confirm('Are you sure you want to restore this patient?')) {
    router.post(`/patients/${id}/restore`, {}, {
      onSuccess: () => {
        router.visit('/patients/archive') // refresh archive page
      }
    })
  }
}


const forceDeletePatient = (id) => {
    if (confirm('⚠️ Permanently delete this patient? This cannot be undone.')) {
        router.delete(`/patients/${id}/force-delete`, {
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
        <div class="p-6 space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <h1 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                    Archived Patients
                </h1>
                <Link href="/patients" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                ← Back to Patients
                </Link>
            </div>

            <!-- Archived Patients Table -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700 border-b text-sm">
                            <th class="px-4 py-2">Patient No.</th>
                            <th class="px-4 py-2">Name</th>
                            <th class="px-4 py-2">Gender</th>
                            <th class="px-4 py-2">Deleted At</th>
                            <th class="px-4 py-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="patient in patients.data" :key="patient.id"
                            class="border-b hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-4 py-2">{{ patient.id }}</td>
                            <td class="px-4 py-2">
                                {{ patient.first_name }} {{ patient.last_name }}
                            </td>
                            <td class="px-4 py-2">{{ patient.gender }}</td>
                            <td class="px-4 py-2 text-red-500">
                                {{ formatDateTime(patient.deleted_at) }}
                            </td>
                            <td class="px-4 py-2">
                                <div class="relative inline-block text-left dropdown-container">
                                    <button @click.stop="toggleDropdown(patient.id)"
                                        class="px-3 py-1 bg-gray-200 dark:bg-gray-700 text-sm rounded">
                                        Action ▼
                                    </button>

                                    <!-- Dropdown -->
                                    <transition enter-active-class="transition ease-out duration-100"
                                        enter-from-class="transform opacity-0 scale-95"
                                        enter-to-class="transform opacity-100 scale-100"
                                        leave-active-class="transition ease-in duration-75"
                                        leave-from-class="transform opacity-100 scale-100"
                                        leave-to-class="transform opacity-0 scale-95">
                                        <div v-if="openDropdown === patient.id"
                                            class="absolute mt-2 right-0 w-40 bg-white dark:bg-gray-700 shadow-lg rounded-md z-50">
                                            <button @click="restorePatient(patient.id)"
                                                class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">
                                                Restore
                                            </button>

                                            <button @click="forceDeletePatient(patient.id)"
                                                class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600">
                                                Delete Permanently
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
                        <Link v-if="link.url" :href="link.url" v-html="link.label" class="px-3 py-1 rounded-lg text-sm"
                            :class="{
                                'bg-blue-600 text-white': link.active,
                                'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300': !link.active,
                            }" />
                        <span v-else v-html="link.label" class="px-3 py-1 text-sm text-gray-400" />
                    </template>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
