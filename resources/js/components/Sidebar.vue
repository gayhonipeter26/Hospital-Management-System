<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3'
import { Home, Users, Activity, Settings, LogOut, ClipboardList } from 'lucide-vue-next'

// Define a User type (extend this later if needed)
interface User {
  id: number
  name: string
  email: string
  role?: string
}

// Get the logged in user from Inertia shared props
const page = usePage<{ auth: { user: User | null } }>()
const user = page.props.auth.user
</script>

<template>
  <aside class="w-64 bg-gray-900 text-white min-h-screen flex flex-col">
    <!-- Logo / Title -->
    <div class="p-4 text-xl font-bold border-b border-gray-700">
      Hospital System
    </div>

    <!-- Navigation -->
    <nav class="flex-1 p-2 space-y-2">
      <Link href="/dashboard" class="flex items-center gap-2 p-2 rounded hover:bg-gray-700">
        <Home class="w-4 h-4" /> Dashboard
      </Link>

      <!-- Admin only links -->
      <template v-if="user?.role === 'admin'">
        <Link href="/patients" class="flex items-center gap-2 p-2 rounded hover:bg-gray-700">
          <Users class="w-4 h-4" /> Patients
        </Link>

        <Link href="/visits" class="flex items-center gap-2 p-2 rounded hover:bg-gray-700">
          <ClipboardList class="w-4 h-4" /> Patients Visits
        </Link>

        <Link href="/triage" class="flex items-center gap-2 p-2 rounded hover:bg-gray-700">
          <Activity class="w-4 h-4" /> Triage
        </Link>

        <Link href="/consultations" class="flex items-center gap-2 p-2 rounded hover:bg-gray-700">
          <Activity class="w-4 h-4" /> Consultation
        </Link>
      </template>
    </nav>

    <!-- Footer (always visible) -->
    <div class="p-2 border-t border-gray-700">
      <Link href="/settings" class="flex items-center gap-2 p-2 rounded hover:bg-gray-700">
        <Settings class="w-4 h-4" /> Settings
      </Link>
      <Link href="/logout" method="post" as="button" class="flex items-center gap-2 p-2 rounded hover:bg-gray-700">
        <LogOut class="w-4 h-4" /> Logout
      </Link>
    </div>
  </aside>
</template>
