<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { 
    BookOpen, 
    Folder, 
    LayoutGrid, 
    Users, 
    Activity, 
    ClipboardList,
    Stethoscope,
    Syringe,
    Microscope,
    HeartPulse,
    Shield,
    FileText
} from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const page = usePage()
const user = page.props.auth.user

// default nav
const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
]

// admin-only links
if (user?.role === 'admin') {
    mainNavItems.push(
        {
            title: 'Patients',
            href: '/patients',
            icon: Users,
        },
        {
            title: 'Patients Visits',
            href: '/visits',
            icon: ClipboardList,
        },
        {
            title: 'Triage',
            href: '/triage',
            icon: Activity,
        },
        {
            title: 'Consultations',
            href: '/consultations',
            icon: Stethoscope,
        },
        {
            title: 'Laboratory',
            href: '/laboratory',
            icon: Microscope,
        },
        {
            title: 'Pharmacy',
            href: '/pharmacy',
            icon: Syringe,
        },
        {
            title: 'Radiology',
            href: '/radiology',
            icon: HeartPulse,
        },
        {
            title: 'Billing',
            href: '/billing',
            icon: FileText,
        },

    )
}

</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <!-- Logo -->
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <!-- Navigation (role aware) -->
        <SidebarContent>
            <div class="px-2 py-4 space-y-3"> 
                <!-- Added space between nav items -->
                <NavMain :items="mainNavItems" />
            </div>
        </SidebarContent>

        <!-- Footer -->
        <SidebarFooter>
            <div class="space-y-2">
               <NavFooter :items="items || []" />

                <NavUser />
            </div>
        </SidebarFooter>
    </Sidebar>

    <!-- Page content -->
    <slot />
</template>
