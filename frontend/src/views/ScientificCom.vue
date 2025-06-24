<template>
    <component :is="componentToShow" />
</template>

<script setup>
import ScientificCom from '@/components/ScientificCommittee.vue';
import OrganizingCom from '@/components/OrganizingCom.vue';
import { defineProps, computed } from 'vue';

const props = defineProps({
  committeeType: {
    type: String,
    default: 'scientific'
  }
});

const componentToShow = computed(() =>
  props.committeeType === 'organizing'
    ? OrganizingCom
    : ScientificCom
);
</script>