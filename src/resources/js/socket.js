import { reactive } from "vue";
import { io } from "socket.io-client";

export const state = reactive({
    connected: false,
    messages: [],
    barEvents: []
});

// "undefined" means the URL will be computed from the `window.location` object
const URL = import.meta.env.VITE_PUSHER_HOST+":"+import.meta.env.VITE_PUSHER_PORT;

export const socket = io(URL);

socket.on("connect", () => {
    state.connected = true;
});

socket.on("disconnect", () => {
    state.connected = false;
});

socket.on("message", (...args) => {
    state.fooEvents.push(args);
});

