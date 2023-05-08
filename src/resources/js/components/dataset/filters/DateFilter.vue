<script setup>
import {store} from "../../../store/store";
import {reactive, defineEmits} from "vue";

const emits = defineEmits(['change']);

const range = reactive({min: 10, max: 10, birthDate: null, setData: ""});
const flows = reactive({
    age: {title: "Age", active: true},
    ageRange: {title: "Age Rnge", active: false},
    birthDate: {title: "Date of birth", active: false}
});

function setActive(val) {
    for (let i in flows) {
        flows[i].active = i === val;
    }
}

function setData(data) {
    range.setData = data;
}

function removeFilter() {
    store.resetDates();
    range.setData = "";
}

function setBirthDate() {
    if (range.birthDate) {
        store.setBirthDate(range.birthDate);
        setData('Date ' + range.birthDate);
    }
}
</script>

<template>
    <div>
        Date Filters
    </div>
    <div >
    <span @click="setActive(key)" :class="flow.active===true?'badge bg-primary':'badge bg-secondary'"
          v-for="(flow,key) in flows">
        {{ flow.title }}
    </span>
    </div>
    <div v-if="flows.age.active">
        <input v-model.number="range.min" type="range" min="0" max="100">
        {{ range.min }}&nbsp;
        <button class="btn btn-primary" @click="store.setAge(range.min); setData('Age '+range.min); $emit('change')">Set</button>
    </div>
    <div v-if="flows.ageRange.active">
        <input v-model.number="range.min" type="range" min="0" max="100">
        {{ range.min }}
        <input v-model.number="range.max" type="range" :min="range.min" max="100">
        {{ range.max }}&nbsp;
        <button class="btn btn-primary"
            @click="store.setAge(range.min,range.max); setData('Age between '+range.min+','+range.max); $emit('change')">
            Set
        </button>
    </div>
    <div v-if="flows.birthDate.active">
        <input type="date" v-model="range.birthDate">&nbsp;
        <button class="btn btn-primary" @click="setBirthDate(); $emit('change')">Set</button>
    </div>
    <span class="badge bg-success" v-if="range.setData" @click="removeFilter">- {{ range.setData }}</span>
</template>

<style scoped lang="scss">

</style>
