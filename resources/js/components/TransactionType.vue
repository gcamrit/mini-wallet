<script setup lang="ts">
interface Props {
    type: "SENT" | "RECEIVED"
}
defineProps<Props>()
</script>

<template>
      <span
          class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold"
          :class="{
              'bg-green-100 text-green-700':  type === 'RECEIVED',
              'bg-red-100 text-red-700':  type === 'SENT',
            }"
      >
        {{ type }}
      </span>
</template>
