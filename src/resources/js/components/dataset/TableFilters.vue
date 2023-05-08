<script setup>
import {store} from '../../store/store.ts'
import {ref} from 'vue';
import MultipleFilter from "./filters/MultipleFilter.vue";
import DateFilter from "./filters/DateFilter.vue";

const props = defineProps({});
const emit = defineEmits(['change'])

const categories = ref(null);
const genders = ref(null);
const age = ref([]);
fetch('/api/dataset/category')
    .then(response => response.json())
    .then(data => categories.value = data);

fetch('/api/dataset/gender')
    .then(response => response.json())
    .then(data => genders.value = data);

function update() {
    emit('change');
}

</script>

<template>
    <div class="filters card mb-2">
        <div class="card-header">Filters</div>
        <div class="card-body">
            <multiple-filter
                :items="categories"
                :store="store"
                itemName="category"
                title="Category"
                @click="update"
            ></multiple-filter>
            <multiple-filter
                :items="genders"
                :store="store"
                title="Gender"
                itemName="gender"
                @click="update"></multiple-filter>
            <date-filter @change="update"></date-filter>
        </div>
    </div>
</template>

<style lang="scss">

</style>
