<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref } from "vue"
import { Link } from "@inertiajs/vue3"

defineProps({ patient: Object })

// Tab state
const activeTab = ref("basic")

// Modal state
const showModal = ref(false)
const currentTab = ref("")

function openModal(tab) {
  currentTab.value = tab
  showModal.value = true
}
function closeModal() {
  showModal.value = false
  currentTab.value = ""
}
</script>

<template>
  <AppLayout>
    <div class="p-6 space-y-6">
      <!-- Back Link -->
      <div class="mb-4">
        <Link href="/patients" class="text-blue-600 hover:underline">&larr; Back to Patients</Link>
      </div>

      <!-- Top Section -->
      <div class="grid grid-cols-3 gap-6">

        <!-- Profile -->
        <div class="col-span-1 bg-white dark:bg-gray-800 p-6 rounded-lg shadow flex flex-col items-center">
          <img
            :src="`https://ui-avatars.com/api/?name=${patient.first_name}+${patient.last_name}&background=0D8ABC&color=fff`"
            alt="Avatar" class="w-20 h-20 rounded-full mb-3" />
          <h2 class="font-bold text-lg">{{ patient.first_name }} {{ patient.last_name }}</h2>
          <p class="text-gray-500 text-sm">{{ patient.email ?? 'No email' }}</p>
        </div>

        <!-- Patient Info -->
        <div class="col-span-1 bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
          <div class="grid grid-cols-2 gap-3 text-sm">
            <p><strong>Gender:</strong> {{ patient.gender }}</p>
            <p><strong>DOB:</strong> {{ patient.dob }}</p>
            <p><strong>Phone:</strong> {{ patient.phone_code }} {{ patient.phone }}</p>
            <p><strong>Address:</strong> {{ patient.residence }}</p>
            <p><strong>City:</strong> {{ patient.city ?? 'N/A' }}</p>
            <p><strong>Country:</strong> {{ patient.country }}</p>
          </div>
        </div>

        <!-- Notes -->
        <div class="col-span-1 bg-white dark:bg-gray-800 p-6 rounded-lg shadow flex flex-col">
          <div class="flex justify-between items-center mb-3">
            <h2 class="font-semibold">Notes</h2>
            <button @click="openModal('notes')" class="text-blue-600 text-sm">Edit</button>
          </div>
          <p class="text-sm">{{ patient.notes ?? "No notes added." }}</p>
        </div>
      </div>

      <!-- Middle Section with Tabs -->
      <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
        <div class="flex gap-4 border-b mb-4 overflow-x-auto">
          <button @click="activeTab = 'basic'"
            :class="activeTab === 'basic' ? 'border-b-2 border-blue-600 font-semibold' : 'text-gray-500'">Basic
            Info</button>
          <button @click="activeTab = 'identifiers'"
            :class="activeTab === 'identifiers' ? 'border-b-2 border-blue-600 font-semibold' : 'text-gray-500'">Identifiers</button>
          <button @click="activeTab = 'medical'"
            :class="activeTab === 'medical' ? 'border-b-2 border-blue-600 font-semibold' : 'text-gray-500'">Medical
            Info</button>
          <button @click="activeTab = 'visits'"
            :class="activeTab === 'visits' ? 'border-b-2 border-blue-600 font-semibold' : 'text-gray-500'">Visits</button>
          <button @click="activeTab = 'vitals'"
            :class="activeTab === 'vitals' ? 'border-b-2 border-blue-600 font-semibold' : 'text-gray-500'">Vitals</button>
          <button @click="activeTab = 'billing'"
            :class="activeTab === 'billing' ? 'border-b-2 border-blue-600 font-semibold' : 'text-gray-500'">Billing</button>
        </div>

        <!-- Tab Content -->
        <div v-if="activeTab === 'basic'" class="space-y-2 text-sm">
          <div class="flex justify-between items-center">
            <h2 class="font-semibold">Basic Info</h2>
            <button @click="openModal('basic')" class="px-2 py-1 bg-blue-600 text-white rounded text-xs">Edit</button>
          </div>
          <p><strong>Name:</strong> {{ patient.first_name }} {{ patient.last_name }}</p>
          <p><strong>Email:</strong> {{ patient.email ?? "N/A" }}</p>
          <p><strong>Phone:</strong> {{ patient.phone_code }} {{ patient.phone }}</p>
        </div>

        <div v-if="activeTab === 'identifiers'" class="space-y-2 text-sm">
          <div class="flex justify-between items-center">
            <h2 class="font-semibold">Identifiers</h2>
            <button @click="openModal('identifiers')"
              class="px-2 py-1 bg-blue-600 text-white rounded text-xs">Edit</button>
          </div>
          <p><strong>ID Number:</strong> {{ patient.id_number }}</p>
          <p><strong>Nationality:</strong> {{ patient.nationality }}</p>
        </div>

        <div v-if="activeTab === 'medical'" class="space-y-2 text-sm">
          <div class="flex justify-between items-center">
            <h2 class="font-semibold">Medical Info</h2>
            <button @click="openModal('medical')" class="px-2 py-1 bg-blue-600 text-white rounded text-xs">Edit</button>
          </div>
          <p><strong>DOB:</strong> {{ patient.dob }}</p>
          <p><strong>Blood Type:</strong> {{ patient.blood_type ?? 'N/A' }}</p>
          <p><strong>Marital Status:</strong> {{ patient.marital_status ?? 'N/A' }}</p>
          <p><strong>Occupation:</strong> {{ patient.occupation ?? 'N/A' }}</p>
        </div>

        <div v-if="activeTab === 'visits'" class="space-y-2 text-sm">
          <div class="flex justify-between items-center">
            <h2 class="font-semibold">Visits</h2>
            <button @click="openModal('visits')" class="px-2 py-1 bg-blue-600 text-white rounded text-xs">Edit</button>
          </div>
          <p>No visits yet...</p>
        </div>

        <div v-if="activeTab === 'vitals'" class="space-y-2 text-sm">
          <div class="flex justify-between items-center">
            <h2 class="font-semibold">Vitals</h2>
            <button @click="openModal('vitals')" class="px-2 py-1 bg-blue-600 text-white rounded text-xs">Edit</button>
          </div>
          <p>No vitals recorded...</p>
        </div>

        <div v-if="activeTab === 'billing'" class="space-y-2 text-sm">
          <div class="flex justify-between items-center">
            <h2 class="font-semibold">Billing</h2>
            <button @click="openModal('billing')" class="px-2 py-1 bg-blue-600 text-white rounded text-xs">Edit</button>
          </div>
          <p>No billing info yet...</p>
        </div>
      </div>

      <!-- Files + Payments -->
      <div class="grid grid-cols-3 gap-6">
        <!-- Files -->
        <div class="col-span-2 bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
          <div class="flex justify-between items-center mb-3">
            <h2 class="font-semibold">Files / Documents</h2>
            <button @click="openModal('files')" class="text-blue-600 text-sm">Add files</button>
          </div>
          <p class="text-sm">No files uploaded.</p>
        </div>

        <!-- Payments -->
        <div class="col-span-1 bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
          <h2 class="font-semibold mb-3">Payments</h2>
          <p class="text-sm">No payments recorded yet.</p>
        </div>
      </div>

      <!-- Modal -->
      <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-full max-w-md">
          <!-- Header -->
          <div class="flex justify-between items-center mb-4">
            <h2 class="font-semibold text-lg text-gray-800 dark:text-gray-100">Edit {{ currentTab }} Info</h2>
            <button @click="closeModal" class="text-gray-500 hover:text-red-600 text-xl">✖</button>
          </div>

          <!-- Form Fields -->
          <div class="space-y-4">
            <div v-if="currentTab === 'basic'">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">First Name</label>
              <input type="text" placeholder="First Name"
                class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />

              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 mt-3">Last Name</label>
              <input type="text" placeholder="Last Name"
                class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
            </div>

            <div v-if="currentTab === 'identifiers'">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ID Number</label>
              <input type="text" placeholder="ID Number"
                class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />

              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 mt-3">Nationality</label>
              <input type="text" placeholder="Nationality"
                class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
            </div>

            <div v-if="currentTab === 'medical'">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date of Birth</label>
              <input type="date"
                class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />

              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 mt-3">Blood Type</label>
              <input type="text" placeholder="Blood Type"
                class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
            </div>

            <div v-if="currentTab === 'visits'">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Visit Notes</label>
              <textarea placeholder="Add visit notes..."
                class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
            </div>

            <div v-if="currentTab === 'vitals'">
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date Recorded</label>
                  <input type="datetime-local"
                    class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Visit No.</label>
                  <input type="text" placeholder="Visit Number"
                    class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Weight (kg)</label>
                  <input type="number" placeholder="Weight"
                    class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Height (cm)</label>
                  <input type="number" placeholder="Height"
                    class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">BMI</label>
                  <input type="text" placeholder="BMI"
                    class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Blood Pressure</label>
                  <input type="text" placeholder="e.g. 120/80 mmHg"
                    class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pulse (bpm)</label>
                  <input type="number" placeholder="Pulse"
                    class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Respiratory
                    Rate</label>
                  <input type="number" placeholder="Respiratory Rate"
                    class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Temperature
                    (°C)</label>
                  <input type="number" placeholder="Temperature"
                    class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">SpO₂ (%)</label>
                  <input type="number" placeholder="Oxygen Saturation"
                    class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                </div>
              </div>

              <!-- Full-width fields below -->
              <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Comments</label>
                <textarea placeholder="Comments..."
                  class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
              </div>

              <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Disability</label>
                <select class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                  <option value="">Select...</option>
                  <option value="NO">NO</option>
                  <option value="YES">YES</option>
                </select>
              </div>

              <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Substance Use</label>
                <select class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                  <option value="">Select...</option>
                  <option value="NONE">NONE</option>
                  <option value="ALCOHOL_USE">ALCOHOL_USE</option>
                  <option value="TOBACCO_USE">TOBACCO_USE</option>
                  <option value="DRUG_USE">DRUG_USE</option>
                </select>
              </div>
            </div>


            <div v-if="currentTab === 'billing'" class="space-y-4">
              <!-- Simple Billing Details -->
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  Billing Type
                </label>
                <select class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-300 
             dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                  <option value="">Select...</option>
                  <option value="Consultation">Consultation</option>
                  <option value="Treatment">Treatment</option>
                  <option value="Surgery">Surgery</option>
                  <option value="Lab Tests">Lab Tests</option>
                  <option value="Pharmacy">Pharmacy</option>
                  <option value="Other">Other</option>
                </select>
              </div>

              <!-- Insurance Info -->
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  Insurance Provider
                </label>
                <select class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-300 
             dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                  <option value="">Select...</option>
                  <option value="Jubilee">Jubilee Insurance</option>
                  <option value="AAR">AAR Insurance</option>
                  <option value="NHIF">NHIF</option>
                  <option value="Britam">Britam</option>
                  <option value="CIC">CIC Insurance</option>
                  <option value="Other">Other</option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  Policy Number
                </label>
                <input type="text" placeholder="e.g. POL-123456" class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-300 
             dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
              </div>

              <!-- Coverage -->
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  Coverage Type
                </label>
                <select class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-300 
             dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                  <option value="">Select...</option>
                  <option value="Full">Full Coverage</option>
                  <option value="Partial">Partial Coverage</option>
                  <option value="Co-pay">Co-pay</option>
                  <option value="None">None</option>
                </select>
              </div>

              <!-- Payment -->
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  Payment Method
                </label>
                <select class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-300 
             dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                  <option value="">Select...</option>
                  <option value="Cash">Cash</option>
                  <option value="Card">Credit/Debit Card</option>
                  <option value="M-Pesa">M-Pesa</option>
                  <option value="Bank Transfer">Bank Transfer</option>
                  <option value="Cheque">Cheque</option>
                  <option value="Insurance">Insurance</option>
                </select>
              </div>

              <!-- Amount + Status -->
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Amount Due
                  </label>
                  <input type="number" placeholder="0.00" class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-300 
               dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Payment Status
                  </label>
                  <select class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-300 
               dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Select...</option>
                    <option value="Pending">Pending</option>
                    <option value="Paid">Paid</option>
                    <option value="Partially Paid">Partially Paid</option>
                    <option value="Overdue">Overdue</option>
                  </select>
                </div>
              </div>

              <!-- Notes -->
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  Billing Notes
                </label>
                <textarea placeholder="Additional billing details..." class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-300 
             dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
              </div>
            </div>


            <div v-if="currentTab === 'notes'">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes</label>
              <textarea placeholder="Update notes..."
                class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
            </div>

            <div v-if="currentTab === 'files'">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Upload File</label>
              <input type="file"
                class="w-full border rounded-lg p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
            </div>
          </div>

          <!-- Actions -->
          <div class="flex justify-end mt-6 gap-2">
            <button @click="closeModal"
              class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">Cancel</button>
            <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
