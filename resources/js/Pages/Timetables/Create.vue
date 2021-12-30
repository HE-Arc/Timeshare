<template>
    <p>Create Timetable</p>
    <form v-on:submit.prevent="onSubmit" method="POST">
        <input type="hidden" name="_token" :value="csrf">
        <p>
            <label for="inputIsPublic">Public</label>
            <input v-model="isPublic" type="checkbox" name="isPublic" id="inputIsPublic"/>
        </p>

        <p>
            <label for="inputTitle">Titre</label>
            <input v-model="title" type="text" name="title" id="inputTitle"/>
        </p>

        <p><button type="submit" class="btn btn-primary mt-3">Envoyer</button></p>
    </form>
</template>


<script>
import { defineComponent } from 'vue'
import { Inertia } from '@inertiajs/inertia'

export default defineComponent({
    props: [

    ],
    data() {
        return {
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            title: "",
            isPublic: false
        }
    },
    methods:{
        onSubmit(){
            console.log(this.isPublic, this.title);
            Inertia.post(route("timetables.store", {isPublic:this.isPublic, title:this.title}));
        }
    }
})
</script>
