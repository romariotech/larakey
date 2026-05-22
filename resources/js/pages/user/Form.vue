<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { toTypedSchema } from '@vee-validate/zod';
import { ArrowLeft, Save } from 'lucide-vue-next';
import { useForm } from 'vee-validate';
import { ref, computed } from 'vue';
import { toast } from 'vue-sonner';
import * as z from 'zod';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import {
    FormControl,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Switch } from '@/components/ui/switch';
import { index } from '@/routes/users';
import { useUserService } from '@/services/userService';
import type { User } from '@/types';

const props = defineProps<{
    user?: User;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Users',
                href: index(),
            },
            {
                title: 'Form',
            },
        ],
    },
});

const formSchema = toTypedSchema(
    z.object({
        id: z.number().optional(),
        username: z
            .string()
            .min(1, 'Username is required')
            .min(3, 'Username must be at least 3 characters'),
        email: z
            .string()
            .min(1, 'Email is required')
            .email('Invalid email format'),
        first_name: z.string().min(1, 'First name is required'),
        last_name: z.string().min(1, 'Last name is required'),
        role: z.enum(['admin', 'manager', 'user']),
        enabled: z.boolean().default(true),
    }),
);

const form = useForm({
    validationSchema: formSchema,
    initialValues: {
        id: props.user?.id,
        username: props.user?.username || '',
        email: props.user?.email || '',
        first_name: props.user?.first_name || '',
        last_name: props.user?.last_name || '',
        role: (props.user?.role as 'admin' | 'manager' | 'user') || 'user',
        enabled: props.user?.enabled ?? true,
    },
});

const isEdit = computed(() => !!props.user?.id);
const isLoading = ref(false);

const roles = [
    { value: 'admin', label: 'Admin' },
    { value: 'manager', label: 'Manager' },
    { value: 'user', label: 'User' },
];

const onSubmit = form.handleSubmit(async (values) => {
    isLoading.value = true;

    try {
        if (isEdit.value) {
            await useUserService().update(props.user!.id, values);
        } else {
            await useUserService().create(values);
        }

        toast.success(isEdit.value ? 'User updated' : 'User created');
    } catch (error: any) {
        if (error.response?.status === 422) {
            const validationErrors = error.response.data.errors;
            Object.entries(validationErrors).forEach(
                ([field, message]: [string, any]) => {
                    const errorMessage = Array.isArray(message)
                        ? message[0]
                        : message;
                    form.setFieldError(
                        field as
                            | 'username'
                            | 'email'
                            | 'first_name'
                            | 'last_name'
                            | 'role'
                            | 'enabled',
                        errorMessage,
                    );
                },
            );
            toast.error('Validation error', {
                description: 'Please check the fields below',
            });
        } else {
            toast.error(
                `Error ${isEdit.value ? 'updating' : 'creating'} user`,
                {
                    description:
                        error instanceof Error ? error.message : 'Try again',
                },
            );
        }
    } finally {
        isLoading.value = false;
    }
});
</script>

<template>
    <div class="space-y-6">
        <Card>
            <CardHeader>
                <CardTitle>
                    {{ isEdit ? 'Edit User' : 'New User' }}
                </CardTitle>
                <CardDescription>
                    {{
                        isEdit
                            ? 'Update the user details below. Make sure to save your changes.'
                            : 'Fill in the details below to create a new user.'
                    }}
                </CardDescription>
            </CardHeader>
            <CardContent>
                <form
                    @submit.prevent="onSubmit"
                    class="space-y-8"
                >
                    <div
                        class="grid grid-cols-1 items-start gap-2 sm:grid-cols-2 lg:grid-cols-3"
                    >
                        <FormField
                            v-slot="{ componentField }"
                            name="first_name"
                        >
                            <FormItem>
                                <FormLabel>First Name *</FormLabel>
                                <FormControl>
                                    <Input
                                        v-bind="componentField"
                                        placeholder="John"
                                        :disabled="isLoading"
                                        type="text"
                                    />
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        </FormField>

                        <FormField
                            v-slot="{ componentField }"
                            name="last_name"
                        >
                            <FormItem>
                                <FormLabel>Last Name *</FormLabel>
                                <FormControl>
                                    <Input
                                        v-bind="componentField"
                                        placeholder="Doe"
                                        :disabled="isLoading"
                                        type="text"
                                    />
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        </FormField>

                        <FormField
                            v-slot="{ componentField }"
                            name="username"
                        >
                            <FormItem>
                                <FormLabel>Username *</FormLabel>
                                <FormControl>
                                    <Input
                                        v-bind="componentField"
                                        placeholder="john_doe"
                                        :disabled="isLoading || isEdit"
                                        type="text"
                                    />
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        </FormField>
                    </div>
                    <div
                        class="grid grid-cols-1 items-start gap-2 sm:grid-cols-2 lg:grid-cols-6"
                    >
                        <FormField
                            v-slot="{ componentField }"
                            name="email"
                        >
                            <FormItem class="col-span-full lg:col-span-2">
                                <FormLabel>Email *</FormLabel>
                                <FormControl>
                                    <Input
                                        v-bind="componentField"
                                        placeholder="john@example.com"
                                        :disabled="isLoading"
                                        type="email"
                                    />
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        </FormField>

                        <FormField
                            v-slot="{ componentField }"
                            name="role"
                        >
                            <FormItem>
                                <FormLabel>Role *</FormLabel>
                                <Select
                                    :model-value="componentField.modelValue"
                                    @update:model-value="
                                        componentField['onUpdate:modelValue']
                                    "
                                >
                                    <FormControl class="w-full">
                                        <SelectTrigger :disabled="isLoading">
                                            <SelectValue
                                                placeholder="Select a role"
                                            />
                                        </SelectTrigger>
                                    </FormControl>
                                    <SelectContent>
                                        <SelectItem
                                            v-for="r in roles"
                                            :key="r.value"
                                            :value="r.value"
                                        >
                                            {{ r.label }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                                <FormMessage />
                            </FormItem>
                        </FormField>

                        <FormField
                            v-slot="{ componentField }"
                            name="enabled"
                        >
                            <FormItem>
                                <FormLabel class="invisible">Status</FormLabel>

                                <div
                                    class="flex h-9 items-center justify-between rounded-md border border-slate-200 px-4"
                                >
                                    <div>
                                        <FormLabel
                                            class="mt-0! cursor-pointer font-semibold"
                                        >
                                            Enabled
                                        </FormLabel>
                                    </div>
                                    <FormControl>
                                        <Switch
                                            :model-value="
                                                componentField.modelValue
                                            "
                                            @update:model-value="
                                                componentField[
                                                    'onUpdate:modelValue'
                                                ]
                                            "
                                            :disabled="isLoading"
                                        />
                                    </FormControl>
                                </div>

                                <FormMessage />
                            </FormItem>
                        </FormField>
                    </div>

                    <div class="h-px bg-slate-200/50"></div>

                    <div
                        class="flex flex-col gap-2 sm:flex-row sm:justify-between"
                    >
                        <Link :href="index().url">
                            <Button
                                type="button"
                                variant="outline"
                                :disabled="isLoading"
                            >
                                <ArrowLeft class="mr-2 h-4 w-4" />
                                Back
                            </Button>
                        </Link>
                        <div class="flex flex-wrap gap-2">
                            <Button
                                type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700"
                                :disabled="isLoading"
                            >
                                <Save class="mr-2 h-4 w-4" />
                                {{
                                    isLoading
                                        ? 'Saving...'
                                        : isEdit
                                          ? 'Update'
                                          : 'Create'
                                }}
                            </Button>
                        </div>
                    </div>
                </form>
            </CardContent>
        </Card>
    </div>
</template>
