<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { Pencil, Trash2 } from 'lucide-vue-next';
import { ref } from 'vue';
import { toast } from 'vue-sonner';
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger,
} from '@/components/ui/alert-dialog';
import { Button } from '@/components/ui/button';
import { show } from '@/routes/users';
import { useUserService } from '@/services/userService';
import type { User } from '@/types';

const props = defineProps<{
    user: User;
    table: any;
}>();

const isDeleting = ref(false);
const open = ref(false);

const handleEdit = () => {
    router.visit(show({ id: props.user.id }).url);
};

const handleDelete = async () => {
    isDeleting.value = true;

    try {
        await useUserService().destroy(props.user?.id);
        toast.success('User deleted successfully');
        props.table.options.meta?.refreshData();
    } catch (error: any) {
        toast.error('Error deleting user', {
            description:
                error.response?.data?.message || 'Please try again later.',
        });
    } finally {
        isDeleting.value = false;
    }
};
</script>

<template>
    <div class="flex gap-2">
        <Button variant="outline" size="icon-sm" @click="handleEdit">
            <Pencil class="size-4" />
        </Button>

        <AlertDialog v-model:open="open">
            <AlertDialogTrigger as-child>
                <Button variant="destructive" size="icon-sm" :disabled="isDeleting">
                    <Trash2 class="size-4" />
                </Button>
            </AlertDialogTrigger>

            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle>Are you absolutely sure?</AlertDialogTitle>
                    <AlertDialogDescription>
                        This action cannot be undone. This will permanently delete
                        <strong>{{ user.full_name }}</strong>'s account and remove their data.
                    </AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <AlertDialogCancel :disabled="isDeleting">Cancel</AlertDialogCancel>
                    <AlertDialogAction
                        @click="handleDelete"
                        class="bg-red-600 text-white hover:bg-red-700"
                        :disabled="isDeleting"
                    >
                        {{ isDeleting ? 'Deleting...' : 'Yes, delete' }}
                    </AlertDialogAction>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>
    </div>
</template>
