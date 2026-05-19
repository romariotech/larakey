
import type { ColumnDef } from '@tanstack/vue-table';
import { formatDate } from '@vueuse/core';
import { h } from 'vue';
import { Badge } from '@/components/ui/badge';
import type { BadgeVariants } from '@/components/ui/badge';
import type { User } from '@/types';
import UserTableActions from './UserTableActions.vue';

export const columns: ColumnDef<User>[] = [
    {
        accessorKey: 'id',
        header: 'ID',
        meta: {
            headerClass: 'text-center',
            cellClass: 'text-center',
        }
    },

    {
        accessorKey: 'full_name',
        header: 'Full Name',
    },

    {
        accessorKey: 'email',
        header: 'E-mail',
    },

    {
        accessorKey: 'role',
        header: 'Role',
        cell: ({ getValue }) => {
            const variants: Record<string, BadgeVariants['variant']> = {
                admin: 'default',
                manager: 'secondary',
                user: 'outline',
            };

            return h(Badge, {
                variant: variants[getValue<string>()] || 'default',
                class: 'capitalize',
            }, {
                default: () => getValue<string>(),
            });
        },
        meta: {
            headerClass: 'text-center',
            cellClass: 'text-center',
        }
    },

    {
        accessorKey: 'enabled',
        header: 'Enabled',
        cell: ({ getValue }) => {
            const isEnabled = getValue<boolean>();

            return h(Badge, {
                variant: isEnabled ? 'success' : 'destructive',
            }, {
                default: () => isEnabled ? 'Active' : 'Inactive',
            });
        },
        meta: {
            headerClass: 'text-center',
            cellClass: 'text-center',
        }
    },

    {
        accessorKey: 'created_at',
        header: 'Created At',
        cell: ({ getValue }) => {
            return formatDate(new Date(getValue<string>()), 'DD/MM/YYYY HH:mm');
        },
        meta: {
            headerClass: 'text-center',
            cellClass: 'text-center',
        }
    },

    {
        id: 'actions',
        header: 'Actions',
        cell: ({ row, table }) => {
            return h(UserTableActions, {
                user: row.original,
                table: table
            });
        },
        meta: {
            headerClass: 'text-center',
            cellClass: 'flex justify-center',
        },
    },
];
