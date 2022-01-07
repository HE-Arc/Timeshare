<template>
    <p>Create Timetable</p>
    <form v-on:submit.prevent="onSubmit" method="POST">
        <input type="hidden" name="_token" :value="csrf">
        <input v-model="begin"
                @change="checkDates()"
                class="form-control" type="datetime-local">

        <input v-model="end"
                @change="checkDates()"
                class="form-control" type="datetime-local">

        <input v-model="title" placeholder="Title" type="text" class="form-control">

        <button type="submit" class="btn btn-primary mt-3">Add</button>
    </form>
</template>


<script>
import { defineComponent } from 'vue'
import { Inertia } from '@inertiajs/inertia'

export default defineComponent({
    props: [
        "clickedDateTime",
        "timetableId"
    ],
    data() {
        return {
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            begin: this.clickedDateTime.toISOString().slice(0, -8),
            end: this.clickedDateTime.toISOString().slice(0, -8),
            title: ""
        }
    },
    mounted(){
        this.checkDates();
        console.log(this.clickedDateTime);
    },
    methods:{
        onSubmit(){
            console.log(this.timetableId);
            Inertia.post(route("events.store", {title:this.title, end:this.end,
                                                begin:this.begin,
                                                timetable:this.timetableId}));

            setTimeout(() => this.$parent.updateTable(), 750);
        },

        checkDates(){
            let endDateTime = new Date(this.end+":00+0000");
            let beginDateTime = new Date(this.begin+":00+0000");

            if(endDateTime.getTime() <= beginDateTime.getTime()){
                endDateTime = new Date(beginDateTime);
                endDateTime.setMinutes(beginDateTime.getMinutes() + 15);

            }

            this.end = endDateTime.toISOString().slice(0,-8);
            this.begin = beginDateTime.toISOString().slice(0,-8);
        }
    }
})
</script>
