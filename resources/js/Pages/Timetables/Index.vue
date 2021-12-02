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

<!-- ca me semble pas tres mvc :((
<button @click="selectComp('Create')" class="btn btn-primary mb-2">+</button>

 comment appeler timetables.show pour mettre le contenu dans currentComponent
<button v-for="timetable in timetables" @click="selectComp(timetable)" :key="timetable.id">{{timetable.title}}</button>-->

<Link :href="route('timetables.create')" class="btn btn-primary mb-2">+</Link>
<Link :href="route('timetables.show', t.id)" v-for="t in timetables" :key="t.id">{{t.title}}</Link>

<div>
    <component :timetable="timetable" :is="myComponent"></component>
</div>

</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import Create from '@/Pages/Timetables/Create.vue'
import Show from '@/Pages/Timetables/Show.vue'
import { Head, Link } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'

export default {

    components: {
        BreezeAuthenticatedLayout,
        Create,
        Show,
        Head,
        Link
    },
    data() {
        return {
            currentComponent: null,
        }
    },
    props: [
        "timetables",
        "timetable",
        "myComponent"
    ],
    methods:{
        selectComp(comp){
            console.log("ouais");
            this.currentComponent = comp;
        }
    }
}
</script>
