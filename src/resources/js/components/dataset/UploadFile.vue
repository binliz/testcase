<script setup>
import {computed, ref, watchEffect} from "vue";
import { socket } from "@/socket";

const chunks = ref([]);
const file = ref(null);
const uploaded = ref(0);
const loadProgress = ref(0);
socket.on("testcase_loaded:newProgress", (arg) => {
    loadProgress.value = arg.message;
});

watchEffect(() => {
    if (chunks.value.length > 0) {
        upload();
    }
});

function select(event) {
    file.value = event.target.files.item(0);
    createChunks();
}

function upload() {
    axios(config.value).then(response => {
        chunks.value.shift();
    }).catch(error => {
    });
}

function createChunks() {
    let size = 1000000, chunkCount = Math.ceil(file.value.size / size);

    for (let i = 0; i < chunkCount; i++) {
        chunks.value.push(file.value.slice(
            i * size, Math.min(i * size + size, file.value.size)));
    }
}

const progress = computed({
    get: () => {
        if (!file.value) {
            return uploaded.value;
        }
        return Math.floor((uploaded.value * 100) / file.value.size);
    },
    set: (newValue) => {
        newValue
    }
});


const formData = computed({
    get: () => {
        let formData = new FormData;

        formData.set('is_last', chunks.value.length === 1);
        formData.set('file', chunks.value[0], `${file.value.name}.part`);

        return formData;
    },
    set: () => {
    }
});
const config = computed({
    get: () => {
        return {
            method: 'POST',
            data: formData.value,
            url: '/api/dataset/upload',
            headers: {
                'Content-Type': 'application/octet-stream'
            },
            onUploadProgress: event => {
                uploaded.value += event.loaded;
            }
        };
    },
    set: () => {
    }
});


</script>
<template>
    <div class="card mb-2">
        <div class="card-header">Create | Dataset</div>
        <div class="card-body">
            <div class="collapse" id="collapseExample">
                       <h6>
                          Loading a CSV file to create a dataset</h6>

                      <p>You can upload external data in .csv format through the user interface.</p>

                      <p>Before uploading a CSV file, please perform the following actions:</p>

                      <p>Review the format requirements. In CSV must be headers row. In headers row must be assigned required columns:
                          <b>[category firstname lastname email gender birthDate]</b></p>
                      <p>Ensure that all column names are present, otherwise the system will give a data loading error.</p>
                      <p>When uploading a file, the system temporarily saves it for processing purposes only. After creating the
                          dataset, the system deletes the file.</p>

                      <p>On the home tab, click "Create | Dataset" and select "CSV File".</p>
                      <p>Click "Choose file", select the file, and click "Open".</p>
                      <p>System will start loading file</p>
                      <p>The system will begin to upload the file, and the progress bar indicator will display the percentage of
                          data saved.</p>
                      <p>After the file is uploaded, the system will begin to process the data and signal it with a list of
                          messages.</p>
                      <p>As the data is processed, it will begin to appear below, and you can immediately start working with it.
                          However, it is recommended to wait for the upload to complete, which the system will notify</p>
            </div>
            <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Info
            </button>
        </div>
        <div class="card-footer">
            <input type="file" @change="select">
            <progress :value="progress" max="100"></progress>
            Parsing progress: <progress :value="loadProgress" max="100"></progress>
        </div>
    </div>
</template>


<style lang="scss"></style>
