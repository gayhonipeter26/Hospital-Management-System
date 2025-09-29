<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref, computed } from "vue";
import { Link } from "@inertiajs/vue3";

// Icons
import { UserIcon, ClipboardDocumentListIcon, PencilSquareIcon, PlusCircleIcon } from "@heroicons/vue/24/outline";

defineProps({ patient: Object });

const activeTab = ref("basic");

// Modal state
const showModal = ref(false);
const modalTab = ref(null);
const isEditing = ref(false);

// Form fields (shared)
const form = ref({
  field1: '',
  field2: '',
  notes: ''
});

// Open modal for a tab (add/edit)
const openModal = (tab, edit = false, data = null) => {
  modalTab.value = tab;
  isEditing.value = edit;

  if (edit && data) {
    // Pre-fill form with existing data
    form.value = { ...data };
  } else {
    // Reset form for new entry
    form.value = { field1: '', field2: '', notes: '' };
  }

  showModal.value = true;
};

// Submit handler
const submitForm = () => {
  console.log(isEditing.value ? "Editing" : "Adding", "for:", modalTab.value, form.value);
  // Example for Inertia:
  // if (isEditing.value) {
  //   Inertia.put(`/patients/${patient.id}/${modalTab.value}/${form.value.id}`, form.value)
  // } else {
  //   Inertia.post(`/patients/${patient.id}/${modalTab.value}`, form.value)
  // }
  showModal.value = false;
};

// Dynamic modal title
const modalTitle = computed(() => {
  const titles = {
    basic: isEditing.value ? "Edit Basic Info" : "Add Basic Info",
    identifiers: isEditing.value ? "Edit Identifier" : "Add Identifier",
    visits: isEditing.value ? "Edit Visit" : "Add Visit Record",
    vitals: isEditing.value ? "Edit Vitals" : "Add Vitals Record",
    records: isEditing.value ? "Edit Record" : "Add Medical Record",
    prescriptions: isEditing.value ? "Edit Prescription" : "Add Prescription",
    labs: isEditing.value ? "Edit Lab Test" : "Add Lab Test",
    procedures: isEditing.value ? "Edit Procedure" : "Add Procedure",
    billing: isEditing.value ? "Edit Billing" : "Add Billing Record",
  };
  return titles[modalTab.value] || "Add Record";
});
</script>

<template>
  <AppLayout>
    <div class="p-6 space-y-6">
      <!-- Header -->
      <div class="flex justify-between items-center">
        <h1 class="text-xl font-bold">Patient Details</h1>
        <div class="space-x-2">
          <Link
            :href="`/patients/${patient.id}/edit`"
            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
          >
            Edit Patient
          </Link>
          <Link
            href="/patients"
            class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600"
          >
            Back
          </Link>
        </div>
      </div>

      <!-- Patient Summary -->
      <div class="grid grid-cols-3 gap-4 bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
        <div>
          <p class="font-bold text-lg">
            {{ patient.first_name }} {{ patient.last_name }}
          </p>
          <p><strong>Gender:</strong> {{ patient.gender ?? 'N/A' }}</p>
          <p><strong>Patient No:</strong> {{ patient.id }}</p>
          <p><strong>D.O.B:</strong> {{ patient.dob ?? 'N/A' }}</p>
          <p><strong>Contact:</strong> {{ patient.phone ?? 'N/A' }}</p>
        </div>
      </div>

      <!-- Tabs -->
      <div class="flex flex-col gap-6">
        <!-- Patient Info Tabs -->
        <div>
          <div class="flex items-center gap-2 mb-2 text-gray-500 font-medium uppercase text-sm">
            <UserIcon class="h-5 w-5" /> Patient Info
          </div>
          <div role="tablist" class="flex gap-6 border-b pb-2 text-sm">
            <button
              v-for="tab in [
                { key:'basic', label:'Basic Info' },
                { key:'identifiers', label:'Identifiers' },
                { key:'contacts', label:'Emergency Contacts' },
                { key:'localities', label:'Localities' },
                { key:'address', label:'Address Details' },
                { key:'nextkin', label:'Next of Kin' }
              ]"
              :key="tab.key"
              @click="activeTab = tab.key"
              :class="activeTab === tab.key 
                ? 'border-b-2 border-blue-500 text-blue-600 font-semibold' 
                : 'text-gray-600 dark:text-gray-300 hover:text-blue-500'"
              class="pb-1"
            >
              {{ tab.label }}
            </button>
          </div>
        </div>

        <!-- Medical Records Tabs -->
        <div>
          <div class="flex items-center gap-2 mb-2 text-gray-500 font-medium uppercase text-sm">
            <ClipboardDocumentListIcon class="h-5 w-5" /> Medical Records
          </div>
          <div role="tablist" class="flex gap-6 border-b pb-2 text-sm">
            <button
              v-for="tab in [
                { key:'visits', label:'Visits' },
                { key:'vitals', label:'Vitals' },
                { key:'records', label:'Medical Records' },
                { key:'prescriptions', label:'Prescriptions' },
                { key:'labs', label:'Lab Tests' },
                { key:'procedures', label:'Procedures' },
                { key:'billing', label:'Billing' }
              ]"
              :key="tab.key"
              @click="activeTab = tab.key"
              :class="activeTab === tab.key 
                ? 'border-b-2 border-blue-500 text-blue-600 font-semibold' 
                : 'text-gray-600 dark:text-gray-300 hover:text-blue-500'"
              class="pb-1"
            >
              {{ tab.label }}
            </button>
          </div>
        </div>
      </div>

      <!-- Tab Content -->
      <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow mt-2">
        <!-- Basic Info -->
        <div v-if="activeTab === 'basic'">
          <h2 class="text-lg font-semibold mb-4">Basic Info</h2>
          <p><strong>Gender:</strong> {{ patient.gender ?? 'N/A' }}</p>
          <div class="flex gap-2 mt-4">
            <button @click="openModal('basic')" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 flex items-center gap-1">
              <PlusCircleIcon class="h-5 w-5" /> Add
            </button>
            <button v-if="patient.gender" @click="openModal('basic', true, { field1: patient.gender })" class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700 flex items-center gap-1">
              <PencilSquareIcon class="h-5 w-5" /> Edit
            </button>
          </div>
        </div>

        <!-- Identifiers -->
        <div v-if="activeTab === 'identifiers'">
          <h2 class="text-lg font-semibold mb-4">Identifiers</h2>
          <p><strong>ID Number:</strong> {{ patient.id_number ?? 'N/A' }}</p>
          <div class="flex gap-2 mt-4">
            <button @click="openModal('identifiers')" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 flex items-center gap-1">
              <PlusCircleIcon class="h-5 w-5" /> Add
            </button>
            <button v-if="patient.id_number" @click="openModal('identifiers', true, { field1: patient.id_number })" class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700 flex items-center gap-1">
              <PencilSquareIcon class="h-5 w-5" /> Edit
            </button>
          </div>
        </div>

        <!-- Visits -->
        <div v-if="activeTab === 'visits'">
          <h2 class="text-lg font-semibold mb-4">Visits</h2>
          <p>No visits yet.</p>
          <div class="flex gap-2 mt-4">
            <button @click="openModal('visits')" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 flex items-center gap-1">
              <PlusCircleIcon class="h-5 w-5" /> Add
            </button>
            <button @click="openModal('visits', true, { notes: 'Follow-up visit' })" class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700 flex items-center gap-1">
              <PencilSquareIcon class="h-5 w-5" /> Edit
            </button>
          </div>
        </div>

        <!-- Vitals -->
        <div v-if="activeTab === 'vitals'">
          <h2 class="text-lg font-semibold mb-4">Vitals</h2>
          <p>No vitals recorded.</p>
          <div class="flex gap-2 mt-4">
            <button @click="openModal('vitals')" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 flex items-center gap-1">
              <PlusCircleIcon class="h-5 w-5" /> Add
            </button>
            <button @click="openModal('vitals', true, { field1: '120/80', field2: '37°C' })" class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700 flex items-center gap-1">
              <PencilSquareIcon class="h-5 w-5" /> Edit
            </button>
          </div>
        </div>

        <!-- Billing -->
        <div v-if="activeTab === 'billing'">
          <h2 class="text-lg font-semibold mb-4">Billing</h2>
          <p>No billing data.</p>
          <div class="flex gap-2 mt-4">
            <button @click="openModal('billing')" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 flex items-center gap-1">
              <PlusCircleIcon class="h-5 w-5" /> Add
            </button>
            <button @click="openModal('billing', true, { field1: 500, field2: 'cash' })" class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700 flex items-center gap-1">
              <PencilSquareIcon class="h-5 w-5" /> Edit
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-lg p-6 relative">
        <h2 class="text-lg font-semibold mb-4">{{ modalTitle }}</h2>

        <!-- Simple dynamic form -->
        <form @submit.prevent="submitForm" class="space-y-4">
          <!-- Basic -->
          <div v-if="modalTab === 'basic'">
            <label class="block text-sm font-medium">Marital Status</label>
            <input v-model="form.field1" type="text" class="w-full border rounded px-3 py-2" />
          </div>

          <!-- Identifiers -->
          <div v-if="modalTab === 'identifiers'">
            <label class="block text-sm font-medium">ID Number</label>
            <input v-model="form.field1" type="text" class="w-full border rounded px-3 py-2" />
          </div>

          <!-- Visits -->
          <div v-if="modalTab === 'visits'">
            <label class="block text-sm font-medium">Visit Notes</label>
            <textarea v-model="form.notes" class="w-full border rounded px-3 py-2"></textarea>
          </div>

          <!-- Vitals -->
          <div v-if="modalTab === 'vitals'">
            <label class="block text-sm font-medium">Blood Pressure</label>
            <input v-model="form.field1" type="text" class="w-full border rounded px-3 py-2" />
            <label class="block text-sm font-medium mt-2">Temperature</label>
            <input v-model="form.field2" type="text" class="w-full border rounded px-3 py-2" />
          </div>

          <!-- Billing -->
          <div v-if="modalTab === 'billing'">
            <label class="block text-sm font-medium">Amount</label>
            <input v-model="form.field1" type="number" class="w-full border rounded px-3 py-2" />
            <label class="block text-sm font-medium mt-2">Payment Mode</label>
            <select v-model="form.field2" class="w-full border rounded px-3 py-2">
              <option value="">Select</option>
              <option value="cash">Cash</option>
              <option value="insurance">Insurance</option>
              <option value="mpesa">M-Pesa</option>
            </select>
          </div>

          <!-- Shared Notes -->
          <div>
            <label class="block text-sm font-medium">Notes</label>
            <textarea v-model="form.notes" class="w-full border rounded px-3 py-2"></textarea>
          </div>

          <!-- Actions -->
          <div class="flex justify-end gap-2 mt-4">
            <button type="button" @click="showModal = false" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
              Cancel
            </button>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
              {{ isEditing ? 'Update' : 'Save' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>
