<script>
export default {
    props: ['meta'],
    methods:{
        click: function (event){
            event.preventDefault();
            this.$emit('linkEvent',event.target.href);
        }
    }
}
</script>

<template>
    <div v-show="meta">
        <div class="mb-2">currnet page - {{meta.current_page}}&nbsp;
        <span class="badge bg-info">rows {{meta.from}}-{{meta.to}} of {{meta.total}}</span>&nbsp;
        <span class="badge bg-info">Pages {{meta.last_page}} per {{meta.per_page}}</span>
</div>


        <nav aria-label="Page navigation example" v-if="meta.links">
            <ul class="pagination">
                <li class="page-item" v-for="link in meta.links">
                    <a :class="link.active?'page-link active':'page-link'" v-on:click="click" class="page-link" :href="link.url"  v-html="link.label">
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</template>

<style scoped>
</style>
