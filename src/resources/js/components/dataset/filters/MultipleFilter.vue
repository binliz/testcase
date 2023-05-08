<script setup>
import {store} from "../../../store/store";

const props = defineProps({
    title: String,
    items: Array,
    store: store,
    itemName: String,
});
const emits = defineEmits(['click']);
</script>

<template>
    <div class="row">
        <div class="col">
            {{ title }}
        </div>
    </div>
    <div class="row">
        <div class="col">
            <span
                class="badge bg-primary"
                v-for="item in items"
                @click="store.setItem(itemName,item);$emit('click')">+ {{ item }}
            </span>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <span
                class="badge bg-success"
                v-for="applied in store[itemName]"
                @click="store.removeItem(itemName,applied); $emit('click')"
            >- {{ applied }}</span>
        </div>
        <div class="col">
            <span class="badge bg-danger" v-if="store[itemName].length>1" @click="store.removeAll(itemName); $emit('click')">remove all</span>
        </div>
    </div>

</template>

<style scoped lang="scss">

</style>
