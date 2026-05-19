<script setup lang="ts" generic="TData, TValue = any">
import type { ColumnDef } from '@tanstack/vue-table';
import { FlexRender, getCoreRowModel, useVueTable } from '@tanstack/vue-table';
import type { AcceptableValue } from 'reka-ui';
import { computed } from 'vue';
import {
    Pagination,
    PaginationContent,
    PaginationEllipsis,
    PaginationItem,
    PaginationNext,
    PaginationPrevious,
} from '@/components/ui/pagination';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import type { PaginatedResponse } from '@/types/global';

const props = withDefaults(
    defineProps<{
        columns: ColumnDef<TData, TValue>[];
        paginator?: PaginatedResponse<TData>;
        perPageOptions?: number[];
        defaultPerPage?: number;
        defaultPage?: number;
    }>(),
    {
        perPageOptions: () => [10, 15, 20, 50, 100],
        defaultPerPage: 10,
        defaultPage: 1,
    },
);

const emit = defineEmits<{
    (e: 'pagination-change', payload: { page: number; per_page: number }): void;
}>();

const table = useVueTable({
    get data() {
        return props.paginator?.data || [];
    },
    get columns() {
        return props.columns;
    },
    getCoreRowModel: getCoreRowModel(),
    manualPagination: true,
    pageCount: props.paginator?.last_page,
    meta: {
        refreshData: () => onPageChange(1),
    },
});

const currentPage = computed(() =>
    Math.max(1, props.paginator?.current_page || props.defaultPage),
);
const currentPerPage = computed(
    () => props.paginator?.per_page || props.defaultPerPage,
);

const onPageChange = (newPage: number) => {
    const validPage = Math.max(
        1,
        Math.min(newPage, props.paginator?.last_page || newPage),
    );

    emit('pagination-change', {
        page: validPage,
        per_page: currentPerPage.value,
    });
};

const onPerPageChange = (newPerPage: AcceptableValue) => {
    const perPage = Number(newPerPage);

    if (perPage !== currentPerPage.value) {
        emit('pagination-change', {
            page: 1,
            per_page: perPage,
        });
    }
};
</script>

<template>
    <div class="space-y-2">
        <div class="overflow-x-auto">
            <Table>
                <TableHeader>
                    <TableRow
                        v-for="headerGroup in table.getHeaderGroups()"
                        :key="headerGroup.id"
                    >
                        <TableHead
                            v-for="header in headerGroup.headers"
                            :key="header.id"
                            :class="
                                (header.column.columnDef.meta as any)
                                    ?.headerClass
                            "
                        >
                            <FlexRender
                                v-if="!header.isPlaceholder"
                                :render="header.column.columnDef.header"
                                :props="header.getContext()"
                            />
                        </TableHead>
                    </TableRow>
                </TableHeader>

                <TableBody>
                    <template v-if="table.getRowModel().rows?.length">
                        <TableRow
                            v-for="row in table.getRowModel().rows"
                            :key="row.id"
                        >
                            <TableCell
                                v-for="cell in row.getVisibleCells()"
                                :key="cell.id"
                                :class="
                                    (cell.column.columnDef.meta as any)
                                        ?.cellClass
                                "
                            >
                                <slot
                                    :name="`cell-${cell.column.id}`"
                                    :row="row.original"
                                    :cell="cell"
                                >
                                    <FlexRender
                                        :render="cell.column.columnDef.cell"
                                        :props="cell.getContext()"
                                    />
                                </slot>
                            </TableCell>
                        </TableRow>
                    </template>
                    <TableRow v-else>
                        <TableCell
                            :colspan="columns.length"
                            class="h-24 text-center"
                        >
                            No records found.
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

        <div
            class="flex flex-col items-center justify-between gap-2 px-2 sm:flex-row"
        >
            <div class="flex items-center space-x-1">
                <p
                    class="text-xs font-medium text-muted-foreground md:text-nowrap"
                >
                    Items per page:
                </p>
                <Select
                    :model-value="currentPerPage.toString()"
                    @update:model-value="onPerPageChange"
                >
                    <SelectTrigger class="h-8 w-17.5">
                        <SelectValue :placeholder="currentPerPage.toString()" />
                    </SelectTrigger>
                    <SelectContent side="top">
                        <SelectItem
                            v-for="pageSize in perPageOptions"
                            :key="pageSize"
                            :value="pageSize.toString()"
                        >
                            {{ pageSize }}
                        </SelectItem>
                    </SelectContent>
                </Select>
            </div>

            <Pagination
                :page="currentPage"
                :total="paginator?.total"
                :items-per-page="currentPerPage"
                :sibling-count="1"
                show-edges
                @update:page="onPageChange"
            >
                <PaginationContent v-slot="{ items }">
                    <PaginationPrevious class="min-w-24" />

                    <template
                        v-for="(item, index) in items"
                        :key="index"
                    >
                        <PaginationItem
                            v-if="item.type === 'page'"
                            :value="item.value"
                            :is-active="item.value === currentPage"
                            @click="onPageChange(item.value)"
                        >
                            {{ item.value }}
                        </PaginationItem>

                        <PaginationEllipsis
                            v-else
                            :index="index"
                        />
                    </template>

                    <PaginationNext class="min-w-24" />
                </PaginationContent>
            </Pagination>

            <div class="text-xs text-muted-foreground md:text-nowrap">
                Showing {{ paginator?.from || 0 }} until
                {{ paginator?.to || 0 }} of {{ paginator?.total || 0 }} records.
            </div>
        </div>
    </div>
</template>
