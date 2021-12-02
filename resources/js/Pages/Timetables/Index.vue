<template>

<Head title="Dashboard" />

<breeze-authenticated-layout>
    <template #header>
        <h2 class="h4 font-weight-bold">
        Livrs
        </h2>
    </template>
</breeze-authenticated-layout>
<h1>Timetable</h1>

<button @click="showTimetable(timetable.id)" v-for="timetable in timetables" :key="timetable.id">{{timetable.title}}</button>
<button @click="showForm">+</button>

<div id='MainContainer'>
    <create v-if="formVisible"></create>
    <show v-else :timetable="currentTimetable">test</show>
</div>

</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import Create from '@/Pages/Timetables/Create.vue'
import Show from '@/Pages/Timetables/Show.vue'
import { Head, Link } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'
import Button from '@/Components/Button.vue'

export default {

    components: {
        BreezeAuthenticatedLayout,
        Create,
        Show,
        Head,
        Link,
        Button
    },
    data() {
        return {
            formVisible: false,
            currentTimetable: null
        }
    },
    props: [
        "timetables"
    ],
    methods:{
        selectComp(comp){
            console.log("ouais");
            this.currentComponent = comp;
            axios.get(route('timetables.create')).then((response) => {
                this.mainComponent = response.data;
                console.log(this.mainComponent)
            })
        },

        showTimetable(id){
            console.log(id);
            axios.get(route('timetables.show', id)).then((response) => {

                this.currentTimetable = response.data;
                console.log(this.currentTimetable)
            })
            this.formVisible=false;
        },

        showForm(){
            this.formVisible=true;
        }
    }
}
</script>
