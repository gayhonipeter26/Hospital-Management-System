<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm, Link } from '@inertiajs/vue3'
import { ref, watch, computed, onMounted, onUnmounted } from 'vue'
import dayjs from 'dayjs'

// --- Form setup ---
const form = useForm({
  first_name: '',
  last_name: '',
  phone_code: '+254',
  phone: '',
  email: '',
  gender: '',
  dob: '',
  age: '',
  id_number: '',
  nationality: '',
  country: '',
  residence: '',
  city: '',
  occupation: '',
  religion: '',
  notes: '',
  status: 'Active',
})

// --- Validation errors ---
const errors = ref({ phone: '', nationality: '' })

// --- AGE ↔ DOB Sync ---
watch(() => form.age, (val) => {
  if (val && !isNaN(val)) {
    form.dob = dayjs().subtract(val, 'year').format('YYYY-MM-DD')
  }
})
watch(() => form.dob, (val) => {
  if (val) {
    const birth = dayjs(val)
    form.age = dayjs().diff(birth, 'year')
  }
})
const isMinor = computed(() => form.age && form.age < 18)

// --- Hardcoded lists ---
const allNationalities = [
  'Kenyan', 'Ugandan', 'Tanzanian', 'Rwandan', 'Nigerian',
  'South African', 'American', 'British', 'Indian', 'Canadian'
]
const allCountries = [
  'Kenya', 'Uganda', 'Tanzania', 'Rwanda', 'Nigeria',
  'South Africa', 'United States', 'United Kingdom', 'India', 'Canada'
]
const allRegions = [
  'Nairobi', 'Mombasa', 'Kisumu', 'Nakuru', 'Eldoret',
  'Thika', 'Meru', 'Nyeri', 'Machakos', 'Garissa'
]

// --- Search fields and dropdown visibility ---
const nationalitySearch = ref('')
const countrySearch = ref('')
const regionSearch = ref('')

const showNationalityList = ref(false)
const showCountryList = ref(false)
const showRegionList = ref(false)

const filteredNationalities = computed(() =>
  allNationalities.filter(n =>
    n.toLowerCase().includes(nationalitySearch.value.toLowerCase())
  )
)
const filteredCountries = computed(() =>
  allCountries.filter(c =>
    c.toLowerCase().includes(countrySearch.value.toLowerCase())
  )
)
const filteredRegions = computed(() =>
  allRegions.filter(r =>
    r.toLowerCase().includes(regionSearch.value.toLowerCase())
  )
)

// --- Close dropdowns when clicking outside ---
const closeDropdowns = (event) => {
  if (!event.target.closest('.dropdown-nationality')) showNationalityList.value = false
  if (!event.target.closest('.dropdown-country')) showCountryList.value = false
  if (!event.target.closest('.dropdown-region')) showRegionList.value = false
}
onMounted(() => document.addEventListener('click', closeDropdowns))
onUnmounted(() => document.removeEventListener('click', closeDropdowns))

// --- Phone validation ---
const validatePhone = () => {
  const phoneRegex = /^[0-9]{7,12}$/
  if (!phoneRegex.test(form.phone)) {
    errors.value.phone = 'Please enter a valid phone number (7–12 digits).'
  } else {
    errors.value.phone = ''
  }
}

// --- Submit ---
const submit = () => {
  if (!form.nationality) {
    errors.value.nationality = 'Nationality is required.'
    return
  }
  validatePhone()
  if (errors.value.phone) return

  // ✅ Fix: prevent NULL country error
  if (!form.country || form.country.trim() === '') {
    form.country = 'Unknown'
  }

  // Ask activation/deactivation
  const confirmStatus = confirm('Do you want to activate this patient?\nClick "OK" to Activate or "Cancel" to Deactivate.')
  form.status = confirmStatus ? 'Active' : 'Inactive'

  // Submit to database
  form.post('/patients', {
    onSuccess: () => {
      alert(`Patient ${form.first_name} has been successfully saved and marked as ${form.status}.`)
    },
    onError: () => {
      alert('There was an error saving the patient. Please try again.')
    },
  })
}
</script>


<template>
  <AppLayout>
    <div class="p-6 space-y-6 bg-black min-h-screen">
      <div class="flex items-center justify-between">
        <h1 class="text-xl font-bold text-white">New Patient</h1>
        <Link
          href="/patients"
          class="px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-700 border border-white/20"
        >
          Back
        </Link>
      </div>

      <div class="bg-gray-900 shadow rounded-lg p-6 border border-white/10">
        <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-4">

          <!-- First Name -->
          <div>
            <label class="block text-sm text-white">First Name *</label>
            <input v-model="form.first_name" required class="border border-white/20 p-2 rounded w-full bg-gray-800 text-white" />
          </div>

          <!-- Last Name -->
          <div>
            <label class="block text-sm text-white">Last Name *</label>
            <input v-model="form.last_name" required class="border border-white/20 p-2 rounded w-full bg-gray-800 text-white" />
          </div>

          <!-- Phone -->
          <div class="md:col-span-2">
            <label class="block text-sm text-white">Phone *</label>
            <div class="flex">
              <select v-model="form.phone_code" class="border border-white/20 p-2 rounded-l bg-gray-800 text-white">
                <option value="+254">🇰🇪 +254</option>
                <option value="+256">🇺🇬 +256</option>
                <option value="+255">🇹🇿 +255</option>
                <option value="+250">🇷🇼 +250</option>
                <option value="+234">🇳🇬 +234</option>
                <option value="+27">🇿🇦 +27</option>
                <option value="+1">🇺🇸 +1</option>
                <option value="+44">🇬🇧 +44</option>
                <option value="+91">🇮🇳 +91</option>
              </select>
              <input v-model="form.phone" @blur="validatePhone" placeholder="712345678"
                required class="border border-white/20 p-2 rounded-r w-full bg-gray-800 text-white" />
            </div>
            <p v-if="errors.phone" class="text-red-400 text-sm mt-1">{{ errors.phone }}</p>
          </div>

          <!-- Email -->
          <div>
            <label class="block text-sm text-white">Email</label>
            <input v-model="form.email" type="email" class="border border-white/20 p-2 rounded w-full bg-gray-800 text-white" />
          </div>

          <!-- Gender -->
          <div>
            <label class="block text-sm text-white">Gender *</label>
            <select v-model="form.gender" required class="border border-white/20 p-2 rounded w-full bg-gray-800 text-white">
              <option value="">Select</option>
              <option>Male</option>
              <option>Female</option>
            </select>
          </div>

          <!-- DOB & Age -->
          <div>
            <label class="block text-sm text-white">Date of Birth *</label>
            <input v-model="form.dob" type="date" required class="border border-white/20 p-2 rounded w-full bg-gray-800 text-white" />
          </div>
          <div>
            <label class="block text-sm text-white">Age</label>
            <input v-model="form.age" type="number" min="0" class="border border-white/20 p-2 rounded w-full bg-gray-800 text-white" />
          </div>

          <!-- ID Number -->
          <div>
            <label class="block text-sm text-white">ID Number <span v-if="!isMinor">*</span></label>
            <input
              v-model="form.id_number"
              :required="!isMinor"
              class="border border-white/20 p-2 rounded w-full bg-gray-800 text-white"
            />
          </div>

          <!-- Nationality Dropdown -->
          <div class="relative dropdown-nationality">
            <label class="block text-sm text-white">Nationality *</label>
            <input
              v-model="nationalitySearch"
              @focus="showNationalityList = true"
              @input="form.nationality = nationalitySearch"
              placeholder="Type or select nationality"
              class="border border-white/20 p-2 rounded w-full bg-gray-800 text-white"
              required
            />
            <ul
              v-if="showNationalityList && filteredNationalities.length"
              class="absolute bg-gray-800 border border-white/10 rounded mt-1 w-full max-h-32 overflow-y-auto z-10"
            >
              <li
                v-for="nat in filteredNationalities"
                :key="nat"
                @click="form.nationality = nat; nationalitySearch = nat; showNationalityList = false"
                class="p-2 hover:bg-gray-700 cursor-pointer text-white"
              >
                {{ nat }}
              </li>
            </ul>
          </div>

          <!-- Country Dropdown (NOT required) -->
          <div class="relative dropdown-country">
            <label class="block text-sm text-white">Country</label>
            <input
              v-model="countrySearch"
              @focus="showCountryList = true"
              @input="form.country = countrySearch"
              placeholder="Type or select country"
              class="border border-white/20 p-2 rounded w-full bg-gray-800 text-white"
            />
            <ul
              v-if="showCountryList && filteredCountries.length"
              class="absolute bg-gray-800 border border-white/10 rounded mt-1 w-full max-h-32 overflow-y-auto z-10"
            >
              <li
                v-for="c in filteredCountries"
                :key="c"
                @click="form.country = c; countrySearch = c; showCountryList = false"
                class="p-2 hover:bg-gray-700 cursor-pointer text-white"
              >
                {{ c }}
              </li>
            </ul>
          </div>

          <!-- Region Dropdown -->
          <div class="relative dropdown-region">
            <label class="block text-sm text-white">Region / Residence *</label>
            <input
              v-model="regionSearch"
              @focus="showRegionList = true"
              @input="form.residence = regionSearch"
              placeholder="Type or select region"
              class="border border-white/20 p-2 rounded w-full bg-gray-800 text-white"
              required
            />
            <ul
              v-if="showRegionList && filteredRegions.length"
              class="absolute bg-gray-800 border border-white/10 rounded mt-1 w-full max-h-32 overflow-y-auto z-10"
            >
              <li
                v-for="r in filteredRegions"
                :key="r"
                @click="form.residence = r; regionSearch = r; showRegionList = false"
                class="p-2 hover:bg-gray-700 cursor-pointer text-white"
              >
                {{ r }}
              </li>
            </ul>
          </div>

          <!-- City -->
          <div>
            <label class="block text-sm text-white">City</label>
            <input v-model="form.city" class="border border-white/20 p-2 rounded w-full bg-gray-800 text-white" />
          </div>

          <!-- Occupation -->
          <div>
            <label class="block text-sm text-white">Occupation</label>
            <input v-model="form.occupation" class="border border-white/20 p-2 rounded w-full bg-gray-800 text-white" />
          </div>

          <!-- Religion -->
          <div>
            <label class="block text-sm text-white">Religion</label>
            <input v-model="form.religion" class="border border-white/20 p-2 rounded w-full bg-gray-800 text-white" />
          </div>

          <!-- Notes -->
          <div class="md:col-span-2">
            <label class="block text-sm text-white">Notes</label>
            <textarea v-model="form.notes" rows="3" class="border border-white/20 p-2 rounded w-full bg-gray-800 text-white"></textarea>
          </div>

          <!-- Actions -->
          <div class="md:col-span-2 flex justify-end space-x-2">
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">Save</button>
            <Link href="/patients" class="bg-gray-800 hover:bg-gray-700 text-white px-4 py-2 rounded border border-white/20">Cancel</Link>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>
