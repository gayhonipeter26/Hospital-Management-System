<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm, Link } from '@inertiajs/vue3'

const props = defineProps({ patient: Object })

const form = useForm({
  first_name: props.patient.first_name,
  last_name: props.patient.last_name,
  phone_code: props.patient.phone_code || '+254', // default if missing
  phone: props.patient.phone,
  email: props.patient.email,
  gender: props.patient.gender,
  dob: props.patient.dob,
  blood_type: props.patient.blood_type,
  marital_status: props.patient.marital_status,
  id_number: props.patient.id_number,
  nationality: props.patient.nationality,
  residence: props.patient.residence,
  city: props.patient.city,
  country: props.patient.country,
  occupation: props.patient.occupation,
  religion: props.patient.religion,
  notes: props.patient.notes,
})

const submit = () => {
  form.put(`/patients/${props.patient.id}`)
}
</script>

<template>
  <AppLayout>
    <div class="p-6 space-y-6">
      <!-- Header -->
      <div class="flex items-center justify-between">
        <h1 class="text-xl font-bold text-gray-200">Edit Patient</h1>
        <Link href="/patients" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Back</Link>
      </div>

      <!-- Form -->
      <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-4">

          <!-- First Name -->
          <div>
            <label class="block text-sm text-white">First Name <span class="text-red-500">*</span></label>
            <input v-model="form.first_name" type="text" class="border p-2 rounded w-full dark:bg-gray-700 dark:text-gray-200" required />
            <span v-if="form.errors.first_name" class="text-red-500 text-sm">{{ form.errors.first_name }}</span>
          </div>

          <!-- Last Name -->
          <div>
            <label class="block text-sm text-white">Last Name <span class="text-red-500">*</span></label>
            <input v-model="form.last_name" type="text" class="border p-2 rounded w-full dark:bg-gray-700 dark:text-gray-200" required />
            <span v-if="form.errors.last_name" class="text-red-500 text-sm">{{ form.errors.last_name }}</span>
          </div>

          <!-- Phone -->
          <div>
            <label class="block text-sm text-white">Phone <span class="text-red-500">*</span></label>
            <div class="flex">
              <!-- Country Code Dropdown -->
              <select v-model="form.phone_code" class="border p-2 rounded-l dark:bg-gray-700 dark:text-gray-200">
                <option value="+254">🇰🇪 +254 (Kenya)</option>
                <option value="+256">🇺🇬 +256 (Uganda)</option>
                <option value="+255">🇹🇿 +255 (Tanzania)</option>
                <option value="+250">🇷🇼 +250 (Rwanda)</option>
                <option value="+234">🇳🇬 +234 (Nigeria)</option>
                <option value="+1">🇺🇸 +1 (USA)</option>
                <option value="+44">🇬🇧 +44 (UK)</option>
                <option value="+91">🇮🇳 +91 (India)</option>
              </select>
              <!-- Phone Input -->
              <input v-model="form.phone" type="text" placeholder="712345678" class="border p-2 rounded-r w-full dark:bg-gray-700 dark:text-gray-200" required />
            </div>
            <span v-if="form.errors.phone" class="text-red-500 text-sm">{{ form.errors.phone }}</span>
          </div>

          <!-- Email (optional) -->
          <div>
            <label class="block text-sm text-white">Email</label>
            <input v-model="form.email" type="email" class="border p-2 rounded w-full dark:bg-gray-700 dark:text-gray-200" />
            <span v-if="form.errors.email" class="text-red-500 text-sm">{{ form.errors.email }}</span>
          </div>

          <!-- Gender -->
          <div>
            <label class="block text-sm text-white">Gender <span class="text-red-500">*</span></label>
            <select v-model="form.gender" class="border p-2 rounded w-full dark:bg-gray-700 dark:text-gray-200" required>
              <option value="">Select Gender</option>
              <option>Male</option>
              <option>Female</option>
            </select>
            <span v-if="form.errors.gender" class="text-red-500 text-sm">{{ form.errors.gender }}</span>
          </div>

          <!-- DOB -->
          <div>
            <label class="block text-sm text-white">Date of Birth <span class="text-red-500">*</span></label>
            <input v-model="form.dob" type="date" class="border p-2 rounded w-full dark:bg-gray-700 dark:text-gray-200" required />
            <span v-if="form.errors.dob" class="text-red-500 text-sm">{{ form.errors.dob }}</span>
          </div>

          <!-- Blood Type (optional) -->
          <div>
            <label class="block text-sm text-white">Blood Type</label>
            <select v-model="form.blood_type" class="border p-2 rounded w-full dark:bg-gray-700 dark:text-gray-200">
              <option value="">Select Blood Type</option>
              <option>A+</option>
              <option>A-</option>
              <option>B+</option>
              <option>B-</option>
              <option>AB+</option>
              <option>AB-</option>
              <option>O+</option>
              <option>O-</option>
            </select>
          </div>

          <!-- Marital Status (optional) -->
          <div>
            <label class="block text-sm text-white">Marital Status</label>
            <select v-model="form.marital_status" class="border p-2 rounded w-full dark:bg-gray-700 dark:text-gray-200">
              <option value="">Select Marital Status</option>
              <option>Single</option>
              <option>Married</option>
              <option>Divorced</option>
              <option>Widowed</option>
            </select>
          </div>

          <!-- ID Number -->
          <div>
            <label class="block text-sm text-white">ID Number <span class="text-red-500">*</span></label>
            <input v-model="form.id_number" type="text" class="border p-2 rounded w-full dark:bg-gray-700 dark:text-gray-200" required />
            <span v-if="form.errors.id_number" class="text-red-500 text-sm">{{ form.errors.id_number }}</span>
          </div>

          <!-- Nationality -->
          <div>
            <label class="block text-sm text-white">Nationality <span class="text-red-500">*</span></label>
            <input v-model="form.nationality" type="text" class="border p-2 rounded w-full dark:bg-gray-700 dark:text-gray-200" required />
            <span v-if="form.errors.nationality" class="text-red-500 text-sm">{{ form.errors.nationality }}</span>
          </div>

          <!-- Residence -->
          <div>
            <label class="block text-sm text-white">Residence <span class="text-red-500">*</span></label>
            <input v-model="form.residence" type="text" class="border p-2 rounded w-full dark:bg-gray-700 dark:text-gray-200" required />
            <span v-if="form.errors.residence" class="text-red-500 text-sm">{{ form.errors.residence }}</span>
          </div>

          <!-- City (optional) -->
          <div>
            <label class="block text-sm text-white">City</label>
            <input v-model="form.city" type="text" class="border p-2 rounded w-full dark:bg-gray-700 dark:text-gray-200" />
          </div>

          <!-- Country -->
          <div>
            <label class="block text-sm text-white">Country <span class="text-red-500">*</span></label>
            <input v-model="form.country" type="text" class="border p-2 rounded w-full dark:bg-gray-700 dark:text-gray-200" required />
            <span v-if="form.errors.country" class="text-red-500 text-sm">{{ form.errors.country }}</span>
          </div>

          <!-- Occupation (optional) -->
          <div>
            <label class="block text-sm text-white">Occupation</label>
            <input v-model="form.occupation" type="text" class="border p-2 rounded w-full dark:bg-gray-700 dark:text-gray-200" />
          </div>

          <!-- Religion (optional) -->
          <div>
            <label class="block text-sm text-white">Religion</label>
            <input v-model="form.religion" type="text" class="border p-2 rounded w-full dark:bg-gray-700 dark:text-gray-200" />
          </div>

          <!-- Notes (optional) -->
          <div class="md:col-span-2">
            <label class="block text-sm text-white">Notes</label>
            <textarea v-model="form.notes" rows="3" class="border p-2 rounded w-full dark:bg-gray-700 dark:text-gray-200"></textarea>
          </div>

          <!-- Actions -->
          <div class="md:col-span-2 flex justify-end space-x-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Update</button>
            <Link href="/patients" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Cancel</Link>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>
