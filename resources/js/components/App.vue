<template>
    <MaintenanceMode v-if="maintenanceMode" />
    <router-view v-else />
</template>

<script setup>
import { computed } from "vue";
import MaintenanceMode from "./MaintenanceMode.vue";

const maintenanceMode = computed(
    () => import.meta.env.VITE_MAINTENANCE_MODE === "true"
);
</script>
