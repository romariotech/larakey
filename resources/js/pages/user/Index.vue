<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { toast } from 'vue-sonner';
import DataTable from '@/components/DataTable.vue';
import Loading from '@/components/Loading.vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { index } from '@/routes/users';
import { create } from '@/routes/users';
import { useUserService } from '@/services/userService';
import type { UserFilters } from '@/services/userService';
import type { PaginatedResponse } from '@/types/global';
import { columns } from './columns';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Users',
                href: index(),
            },
        ],
    },
});

const searchName = ref('');
const paginator = ref<PaginatedResponse<any>>();
const isLoading = ref(false);

const fetchUsers = async (filters?: UserFilters) => {
    isLoading.value = true;

    try {
        paginator.value = await useUserService().getAll({
            ...filters,
            search: searchName.value,
        });
    } catch (error) {
        toast.error('Error loading users.', {
            description: error instanceof Error ? error.message : String(error),
        });
    } finally {
        isLoading.value = false;
    }
};

const onClearFilters = () => {
    searchName.value = '';
    fetchUsers();
};

onMounted(async () => {
    await fetchUsers();
});
</script>

<template>
    <div class="space-y-6">
        <Card>
            <CardHeader>
                <CardTitle>Filters</CardTitle>
                <CardDescription>
                    Search for users by name or email. You can also clear the
                    filters to see all users.
                </CardDescription>
            </CardHeader>
            <CardContent>
                <div class="flex items-end gap-2">
                    <div class="min-w-sm space-y-2">
                        <Label for="search-name"> Search </Label>

                        <Input
                            id="search-name"
                            v-model="searchName"
                            placeholder="e.g. John Doe or john.doe@example.com"
                            :disabled="isLoading"
                        />
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <Button
                            variant="default"
                            @click="fetchUsers()"
                            :disabled="isLoading"
                            class="bg-indigo-600 text-white hover:bg-indigo-700"
                        >
                            {{ isLoading ? 'Loading...' : 'Search' }}
                        </Button>

                        <Button
                            v-if="searchName"
                            variant="outline"
                            @click="onClearFilters"
                        >
                            Limpar
                        </Button>

                        <Button
                            as="a"
                            :href="create().url"
                            variant="default"
                        >
                            Add User
                        </Button>
                    </div>
                </div>
            </CardContent>
        </Card>

        <Card>
            <CardContent class="relative">
                <Loading
                    full-screen
                    v-if="isLoading"
                />
                <DataTable
                    :columns="columns"
                    :paginator="paginator"
                    @pagination-change="fetchUsers"
                />
            </CardContent>
        </Card>
    </div>
</template>
