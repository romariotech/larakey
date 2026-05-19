<script setup lang="ts">
import { Loader2 } from 'lucide-vue-next';
interface Props {
    size?: 'sm' | 'md' | 'lg' | 'xl';
    text?: string;
    fullScreen?: boolean;
}

withDefaults(defineProps<Props>(), {
    size: 'md',
    text: '',
    fullScreen: false,
});

const sizeClasses = {
    sm: 'h-4 w-4 text-primary',
    md: 'h-8 w-8 text-primary',
    lg: 'h-12 w-12 text-primary',
    xl: 'h-16 w-16 text-primary',
};
</script>

<template>
    <div
        v-if="fullScreen"
        class="absolute inset-0 z-50 flex flex-col items-center justify-center bg-background/60"
    >
        <Loader2 :class="['animate-spin', sizeClasses[size]]" />

        <p v-if="text" class="mt-4 text-sm font-medium text-muted-foreground">
            {{ text }}
        </p>
    </div>

    <div v-else class="flex w-full flex-col items-center justify-center p-4">
        <Loader2 :class="['animate-spin', sizeClasses[size]]" />

        <p v-if="text" class="mt-2 text-sm font-medium text-muted-foreground">
            {{ text }}
        </p>
    </div>
</template>
