<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue'
import { Head, useForm, Link } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'

// ✅ Props from controller
const props = defineProps<{
  patients: { id: number; first_name: string; last_name: string }[]
  selectedPatientId?: number | string | null
}>()

// ✅ Form setup
const form = useForm({
  patient_id: '',
  start_date: '',
  status: 'Check In',
  patient_type: 'Out Patient',
  scheduled: false,
  notes: '',
  department: '',
  practitioner: '',
  practitioner_name: '',
  payment_method: 'Cash',
  // insurance fields
  insurance_provider: '',
  policy_number: '',
  member_name: '',
  relation: '',
  id_number: '',
  principal_member_name: '',
  principal_policy_no: '',
  limit: '',
  authorization_code: '',
  remarks: ''
})

// ✅ UI state
const showPolicyDetails = ref(true)
const showPrincipalDetails = ref(false)

// ✅ Auto-fill current date/time & preselect patient
onMounted(() => {
  const now = new Date()
  form.start_date = now.toISOString().slice(0, 16)

  // ✅ Auto-select patient if redirected with selectedPatientId
  if (props.selectedPatientId) {
    form.patient_id = props.selectedPatientId
  }
})

// ✅ Submit handler
const submit = () => {
  if (form.start_date) {
    form.start_date = form.start_date.replace('T', ' ') + ':00'
  }
  form.post('/visits', {
    onSuccess: () => (window.location.href = '/visits'),
    onError: (err) => console.error('Error:', err)
  })
}

// ✅ Payment setter
const setPayment = (method: string) => {
  form.payment_method = method
}
</script>

<template>
  <AppLayout>
    <Head title="New Visit" />

    <div class="p-6 space-y-6 bg-black min-h-screen text-white">
      <!-- Header -->
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">Add New Visit</h1>
        <Link
          href="/visits"
          class="px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded border border-white/20"
        >
          Back
        </Link>
      </div>

      <!-- Form -->
      <div class="bg-gray-900 rounded-lg p-6 border border-white/10 shadow-md">
        <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Patient -->
          <div class="md:col-span-2">
            <label class="block text-sm font-medium mb-1">Patient</label>
            <select
              v-model="form.patient_id"
              required
              class="w-full bg-gray-800 border border-gray-700 rounded p-2"
            >
              <option value="">-- Choose Patient --</option>
              <option v-for="p in props.patients" :key="p.id" :value="p.id">
                {{ p.first_name }} {{ p.last_name }}
              </option>
            </select>
          </div>

          <!-- Start Date -->
          <div>
            <label class="block text-sm font-medium mb-1">Start Date</label>
            <input
              type="datetime-local"
              v-model="form.start_date"
              required
              class="w-full bg-gray-800 border border-gray-700 rounded p-2"
            />
          </div>

          <!-- Status -->
          <div>
            <label class="block text-sm font-medium mb-1">Status</label>
            <select
              v-model="form.status"
              class="w-full bg-gray-800 border border-gray-700 rounded p-2"
            >
              <option>Check In</option>
              <option>Check Out</option>
              <option>Pending</option>
            </select>
          </div>

          <!-- Patient Type -->
          <div>
            <label class="block text-sm font-medium mb-1">Patient Type</label>
            <select
              v-model="form.patient_type"
              class="w-full bg-gray-800 border border-gray-700 rounded p-2"
            >
              <option>Out Patient</option>
              <option>In Patient</option>
            </select>
          </div>

          <!-- Department -->
          <div>
            <label class="block text-sm font-medium mb-1">Department</label>
            <select
              v-model="form.department"
              class="w-full bg-gray-800 border border-gray-700 rounded p-2"
            >
              <option value="">-- Choose Department --</option>
              <option>Triage</option>
              <option>Nursing</option>
              <option>Consultation</option>
              <option>Eye Unit</option>
              <option>Dental</option>
              <option>Pharmacy</option>
              <option>Laboratory</option>
              <option>Radiology</option>
              <option>Emergency</option>
            </select>
          </div>

          <!-- Practitioner -->
          <div>
            <label class="block text-sm font-medium mb-1">Practitioner Name</label>
            <input
              type="text"
              v-model="form.practitioner_name"
              class="w-full bg-gray-800 border border-gray-700 rounded p-2"
            />
          </div>

          <!-- Payment Method -->
          <div class="md:col-span-2">
            <label class="block text-sm font-medium mb-1">Payment Method</label>
            <div class="flex space-x-4">
              <button
                type="button"
                @click="setPayment('Cash')"
                :class="form.payment_method === 'Cash' ? 'bg-red-600' : 'bg-gray-700'"
                class="px-4 py-2 rounded text-white"
              >
                Cash
              </button>
              <button
                type="button"
                @click="setPayment('Insurance')"
                :class="form.payment_method === 'Insurance' ? 'bg-red-600' : 'bg-gray-700'"
                class="px-4 py-2 rounded text-white"
              >
                Insurance
              </button>
            </div>
          </div>

          <!-- INSURANCE SECTION -->
          <transition
            name="slide-down"
            enter-active-class="transition-all duration-500 ease-out"
            leave-active-class="transition-all duration-500 ease-in"
            enter-from-class="opacity-0 -translate-y-4 max-h-0"
            enter-to-class="opacity-100 translate-y-0 max-h-screen"
            leave-from-class="opacity-100 translate-y-0 max-h-screen"
            leave-to-class="opacity-0 -translate-y-4 max-h-0"
          >
            <div
              v-if="form.payment_method === 'Insurance'"
              class="md:col-span-2 bg-gray-800 p-4 rounded-lg border border-gray-700 space-y-4"
            >
              <!-- Policy Details -->
              <div>
                <button
                  type="button"
                  class="flex justify-between w-full text-left font-semibold text-sm bg-gray-700 hover:bg-gray-600 rounded px-3 py-2"
                  @click="showPolicyDetails = !showPolicyDetails"
                >
                  Policy Details
                  <span>{{ showPolicyDetails ? '−' : '+' }}</span>
                </button>
                <transition
                  name="fade-slide"
                  enter-active-class="transition-all duration-300 ease-out"
                  leave-active-class="transition-all duration-300 ease-in"
                  enter-from-class="opacity-0 max-h-0"
                  enter-to-class="opacity-100 max-h-96"
                  leave-from-class="opacity-100 max-h-96"
                  leave-to-class="opacity-0 max-h-0"
                >
                  <div v-if="showPolicyDetails" class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                    <div>
                      <label class="block text-sm font-medium mb-1">Insurance Provider</label>
                      <input
                        v-model="form.insurance_provider"
                        type="text"
                        class="w-full bg-gray-900 border border-gray-700 rounded p-2"
                      />
                    </div>

                    <div>
                      <label class="block text-sm font-medium mb-1">Policy Number</label>
                      <input
                        v-model="form.policy_number"
                        type="text"
                        class="w-full bg-gray-900 border border-gray-700 rounded p-2"
                      />
                    </div>

                    <div>
                      <label class="block text-sm font-medium mb-1">Member Name</label>
                      <input
                        v-model="form.member_name"
                        type="text"
                        class="w-full bg-gray-900 border border-gray-700 rounded p-2"
                      />
                    </div>

                    <div>
                      <label class="block text-sm font-medium mb-1">Relation</label>
                      <input
                        v-model="form.relation"
                        type="text"
                        class="w-full bg-gray-900 border border-gray-700 rounded p-2"
                      />
                    </div>

                    <div>
                      <label class="block text-sm font-medium mb-1">ID/Passport</label>
                      <input
                        v-model="form.id_number"
                        type="text"
                        class="w-full bg-gray-900 border border-gray-700 rounded p-2"
                      />
                    </div>
                  </div>
                </transition>
              </div>

              <!-- Principal Member Details -->
              <div>
                <button
                  type="button"
                  class="flex justify-between w-full text-left font-semibold text-sm bg-gray-700 hover:bg-gray-600 rounded px-3 py-2"
                  @click="showPrincipalDetails = !showPrincipalDetails"
                >
                  Principal Member Details
                  <span>{{ showPrincipalDetails ? '−' : '+' }}</span>
                </button>
                <transition
                  name="fade-slide"
                  enter-active-class="transition-all duration-300 ease-out"
                  leave-active-class="transition-all duration-300 ease-in"
                  enter-from-class="opacity-0 max-h-0"
                  enter-to-class="opacity-100 max-h-96"
                  leave-from-class="opacity-100 max-h-96"
                  leave-to-class="opacity-0 max-h-0"
                >
                  <div v-if="showPrincipalDetails" class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                    <div>
                      <label class="block text-sm font-medium mb-1">Principal Member Name</label>
                      <input
                        v-model="form.principal_member_name"
                        type="text"
                        class="w-full bg-gray-900 border border-gray-700 rounded p-2"
                      />
                    </div>

                    <div>
                      <label class="block text-sm font-medium mb-1">Principal Policy No.</label>
                      <input
                        v-model="form.principal_policy_no"
                        type="text"
                        class="w-full bg-gray-900 border border-gray-700 rounded p-2"
                      />
                    </div>

                    <div>
                      <label class="block text-sm font-medium mb-1">Limit</label>
                      <input
                        v-model="form.limit"
                        type="text"
                        class="w-full bg-gray-900 border border-gray-700 rounded p-2"
                      />
                    </div>

                    <div>
                      <label class="block text-sm font-medium mb-1">Authorization Code</label>
                      <input
                        v-model="form.authorization_code"
                        type="text"
                        class="w-full bg-gray-900 border border-gray-700 rounded p-2"
                      />
                    </div>

                    <div class="md:col-span-2">
                      <label class="block text-sm font-medium mb-1">Remarks</label>
                      <textarea
                        v-model="form.remarks"
                        rows="2"
                        class="w-full bg-gray-900 border border-gray-700 rounded p-2"
                      ></textarea>
                    </div>
                  </div>
                </transition>
              </div>
            </div>
          </transition>

          <!-- Notes -->
          <div class="md:col-span-2">
            <label class="block text-sm font-medium mb-1">Notes/Comments</label>
            <textarea
              v-model="form.notes"
              rows="3"
              class="w-full bg-gray-800 border border-gray-700 rounded p-2"
            ></textarea>
          </div>

          <!-- Buttons -->
          <div class="md:col-span-2 flex justify-end space-x-3">
            <Link
              href="/visits"
              class="px-4 py-2 bg-gray-700 hover:bg-gray-600 border border-white/20 text-white rounded transition"
            >
              Cancel
            </Link>
            <button
              type="submit"
              :disabled="form.processing"
              class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded transition"
            >
              Save Visit
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
.slide-down-enter-active,
.slide-down-leave-active {
  transition: all 0.5s ease;
  overflow: hidden;
}
.slide-down-enter-from,
.slide-down-leave-to {
  opacity: 0;
  transform: translateY(-20px);
  max-height: 0;
}
.slide-down-enter-to,
.slide-down-leave-from {
  opacity: 1;
  transform: translateY(0);
  max-height: 2000px;
}

.fade-slide-enter-active,
.fade-slide-leave-active {
  transition: all 0.3s ease;
  overflow: hidden;
}
.fade-slide-enter-from,
.fade-slide-leave-to {
  opacity: 0;
  max-height: 0;
}
.fade-slide-enter-to,
.fade-slide-leave-from {
  opacity: 1;
  max-height: 1000px;
}
</style>
