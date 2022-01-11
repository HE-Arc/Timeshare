<template>

<Head title="Timetable" />

<breeze-authenticated-layout>

</breeze-authenticated-layout>

<!--
    A FAIRE :
        ajouter un petit engrenage sur les bouton "myTime..."
            qui montrent la fenêtre de management
    -->

<div class="container h-100">

    <div class="row">
        <div v-if="editMode != 0"
             class="col-sm-4 container p-3 my-3 bg-dark text-white">

            <template v-if="editMode == 1">
                <show-managements :managements="currentManagers" :timetableId="currentEditOn"></show-managements>
                <create-management :timetableId="currentEditOn"></create-management>
            </template>

            <template v-else-if="editMode == 2">
                <create-event :clickedDateTime="clickedDateTime" :timetableId="currentEditOn"/>
            </template>

        </div>

        <div class="col">

            <div class="container p-3 my-3 bg-dark text-white">
                <div class="btn-group" role="group" aria-label="Basic checkbox toggle button group">
                    <template v-for="timetable in myTimetables" :key="timetable.id" >
                    <input @change="updateTable" v-model="selected[timetable.id]" type="checkbox" class="btn-check" :id="timetable.id" autocomplete="off"/>
                    <label :for="timetable.id" class="btn btn-outline-primary" >{{timetable.title}}
                        <button @click="updateManagementsList(timetable.id)" class="btn btn-primary" type="button"><i class="bi bi-pencil"></i></button>
                        <button v-if="timetable.isPublic" @click="copyShareToClipBoard(timetable.id)" class="btn btn-primary" type="button"><i class="bi bi-pencil"></i></button>
                    </label>
                    </template>

                    <template v-for="timetable in manageTimetables" :key="timetable.id" >
                    <input @change="updateTable" v-model="selected[timetable.id]" type="checkbox" class="btn-check" :id="timetable.id" autocomplete="off"/>
                    <label :for="timetable.id" class="btn btn-outline-primary" >{{timetable.title}}</label>
                    </template>

                    <template v-if="sharedTimetable != null">
                    <input @change="updateTable" v-model="selected[sharedTimetable.id]" type="checkbox" class="btn-check" :id="sharedTimetable.id" autocomplete="off"/>
                    <label :for="sharedTimetable.id" class="btn btn-outline-primary" >{{sharedTimetable.title}}</label>
                    </template>

                    <button @click="showForm" class="btn btn-primary" type="button">+</button>
                </div>

                <create-timetable v-if="editMode == 3"></create-timetable>
                <template v-else>

                    <vue-cal
                    style="height: 250px"
                    :events="events"
                    :editable-events="{ title: false, drag: false, resize: false, delete: true, create: false }"
                    :cell-click-hold="false"
                    :drag-to-create-event="false"
                    :split-days="split"
                    @cell-dblclick="showEventCreation"
                    @event-delete="deleteEvent"
                    />

                </template>
            </div>
        </div>
    </div>
</div>


</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import CreateTimetable from '@/Pages/Timetables/Create.vue'
import Show from '@/Pages/Timetables/Show.vue'
import { Head, Link } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'
import Button from '@/Components/Button.vue'
import CreateManagement from '@/Pages/Managements/Create.vue'
import ShowManagements from '@/Pages/Managements/Show.vue'
import CreateEvent from '@/Pages/Events/Create.vue'
import Label from '@/Components/Label.vue'
import VueCal from 'vue-cal'
import 'vue-cal/dist/vuecal.css'

export default {

    components: {
        BreezeAuthenticatedLayout,
        CreateTimetable,
        Show,
        Head,
        Link,
        Button,
        CreateManagement,
        ShowManagements,
        Label,
        VueCal,
        CreateEvent
    },
    data() {
        return {
            editMode: 0,
            currentTimetable: null,
            selected: {},
            currentEditOn: -1,
            currentManagers: null,

            events: [],
            clickedDateTime: Date.now(),
            split: [],
        }
    },
    props: [
        "myTimetables",
        "manageTimetables",
        "sharedTimetable"
    ],
    mounted(){
        this.myTimetables.forEach(timetable => {
            this.selected[timetable.id] = false;
        });
        this.manageTimetables.forEach(timetable => {
            this.selected[timetable.id] = false;
        });
    },
    methods:{
        getTheOnlySelected(){
            let keys = Object.keys(this.selected);
            let s = this.selected;
            let selectedTrue = keys.filter(function(k){
                return s[k];
            });

            if(selectedTrue.length != 1)
                return false
            return selectedTrue[0];
        },

        updateTable(){
            this.editMode = 0;
            this.events = [];

            this.split = [];
            (Object.keys(this.selected)).filter(k=>this.selected[k])
                                      .forEach(k=>{
                this.split.push({
                    id: k,
                    label: "ok"
                });

                this.loadTimetableEvents(k);
            });

            console.log(this.events);
        },

        loadTimetableEvents(timetableId){
            axios.get(route('events.timetablesEvents', {timetable:timetableId})).then((response) => {

                let eventObjects = response.data;
                eventObjects.forEach(e => {
                    let vcEvent = this.eventObjectToVueCalEvent(e);
                    vcEvent["split"] = timetableId;
                    //vcEvent["deletable"] =
                    this.events.push(vcEvent);
                });
            });
        },

        eventObjectToVueCalEvent(e){
            let evc = {
                start: e.begin,
                end: e.end,
                title: e.description,
                id: e.id
                };

            if(e.validated == true)
                evc["class"] = "valid";
            else
                evc["class"] = "invalid";

            return evc;
        },

        updateManagementsList(id){
            this.editMode = 1;
            this.currentEditOn = id;

            axios.get(route('managements.timetablesManagers', {timetable:this.currentEditOn})).then((response) => {
                this.currentManagers = response.data;
            });
        },

        showForm(){
            this.editMode = 3;
            Object.keys(this.selected).forEach(v => this.selected[v] = false)
        },

        showEventCreation(d){
            let dateTime = d["date"];
            this.currentEditOn = d["split"];
            this.editMode = 2;
            this.clickedDateTime = dateTime;
        },

        deleteEvent(e){
            axios.delete(route("events.destroy", e.id));
        },

        copyShareToClipBoard(id){
            navigator.clipboard.writeText("http://localhost:8000/timetables/"+id);
        }
    }
}
</script>
