<script>
export default {
    props: ['row']
}
</script>

<template>
    <tr>
        <td>{{row.id}}</td>
        <td>{{row.category}}</td>
        <td>{{row.firstname}}</td>
        <td>{{row.lastname}}</td>
        <td>{{row.gender}}</td>
        <td>{{row.email}}</td>
        <td>{{row.birthDate}}</td>
    </tr>
</template>

<style scoped>

</style>
