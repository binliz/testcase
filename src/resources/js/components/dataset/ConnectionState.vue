<template>
    <p>State of websocket: {{ connected }}</p>
</template>

<script>
import { state } from "@/socket";

export default {
    name: "ConnectionState",

    computed: {
        connected() {
            return state.connected?"connected":"have`nt connect";
        }
    }
}
</script>
