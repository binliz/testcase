<script>
import {socket} from "@/socket";

export default {
    data() {
        return {
            messages: []
        }
    },

    mounted() {
        socket.on("testcase_messages:newMessage", (arg) => {
            this.messages.unshift(arg);
            if (arg.message === 'Parse CSV [done]') {
                window.location.reload();
            }
        });
    }
}
</script>
<template>
    <div class="overflow-auto  h-50" style="max-height: 100px">
        <div v-for="message in messages" v-html="message.message"></div>
    </div>
</template>
