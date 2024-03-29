<script setup lang="ts">
import ActionIcons from '@/Components/Molecules/ActionIcons.vue'
import Pagination from '@/Components/Molecules/Pagination.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Customer } from '@/types'
import { formatCurrency, formatNumber } from '@/utils'
import { Head, Link } from '@inertiajs/vue3'

const { customers } = defineProps<{
    customers: {
        data: Customer[]
        current_page: number
        last_page: number
        prev_page_url: string | null
        next_page_url: string | null
    }
}>()
</script>

<template>
    <Head title="All Cusomters" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2
                    class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
                >
                    All Customers
                </h2>
            </div>
        </template>

        <div v-if="!customers.data.length" class="flex flex-col gap-4">
            <h3 class="text-xl font-bold dark:text-white">
                No customers found.
            </h3>
            <Link
                class="hover:underline dark:text-white"
                :href="route('customers.create')"
                >Add a customer</Link
            >
        </div>

        <div v-else>
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Lifetime Orders</th>
                        <th>Lifetime Spent</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="customer in customers.data" :key="customer.id">
                        <td>
                            <Link
                                :href="route('customers.show', customer.id)"
                                >{{ customer.name }}</Link
                            >
                        </td>
                        <td>{{ customer.email }}</td>
                        <td>{{ formatNumber(customer.lifetime_orders) }}</td>
                        <td>
                            {{ formatCurrency(customer.lifetime_revenue) }}
                        </td>
                        <td>
                            <ActionIcons
                                :entity-id="customer.id"
                                view-route="customers.show"
                                delete-route="customers.destroy"
                            />
                        </td>
                    </tr>
                </tbody>
            </table>
            <Pagination :pagination-data="customers" />
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.table {
    @apply w-full border-collapse dark:text-white;

    th,
    td {
        @apply p-4 text-left dark:bg-gray-700;
    }

    th {
        @apply bg-gray-100 dark:bg-gray-800;
    }

    a {
        @apply text-blue-500 underline hover:no-underline;
    }
}
</style>
