<script>
import OneRowComponent from "./OneRowComponent.vue";
import TablePaginatorComponent from "./TablePaginator.vue";
import TableFilters from "./TableFilters.vue";
import UploadFile from "./UploadFile.vue";
import {store} from '../../store/store.ts'
import ConnectionState from "./ConnectionState.vue";
import Messages from "./Messages.vue";

export default {
    components: {
        Messages,
        ConnectionState,
        'TableFilters': TableFilters,
        OneRowComponent,
        'TablePaginatorComponent': TablePaginatorComponent,
        UploadFile
    },
    data() {
        return {
            rows: [],
            meta: []
        }
    },
    mounted() {
        this.list()
    },
    methods: {
        async list(data = null) {
            await axios.get(data ? data + "&" + store.queryString() : '/api/dataset?' + store.queryString()).then(({data}) => {
                this.rows = data.dataset;
                this.meta = data.meta;
            }).catch(({response}) => {
                console.error(response)
            })
        },
        updateLink(data) {
            this.list(data);
        },
        csvExport(data =null){
            axios.get('/api/dataset-export?' + store.queryString());
        }
    }
}

</script>

<template>
    <div class="p-5">
        <ConnectionState></ConnectionState>
        <messages></messages>
        <UploadFile/>
        <TableFilters @change="updateLink"/>
        <table v-if="rows.length" class="table table-striped">
            <thead>
            <tr>
                <th>id</th>
                <th>category</th>
                <th>firstname</th>
                <th>lastname</th>
                <th>gender</th>
                <th>email</th>
                <th>birthDate</th>
            </tr>
            </thead>
            <tbody>
            <template v-for="row in rows">
                <OneRowComponent :row="row"></OneRowComponent>
            </template>
            </tbody>
        </table>
        <TablePaginatorComponent v-if="rows.length" @linkEvent="updateLink" :meta="meta"></TablePaginatorComponent>
        <button class="btn btn-primary" @click="csvExport" >Export to CSV</button>
    </div>
</template>

<style lang="scss">
</style>
