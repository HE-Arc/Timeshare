<template>
    <div class="container-fluid edit-div py-3">
        <div class="card shadow-sm px-5 py-2 m-3">
            <div class="card body">

                <h2 class="mt-3">Create a Timetable</h2>
                <form v-on:submit.prevent="onSubmit" method="POST">
                    <input type="hidden" name="_token" :value="csrf">
                
                    <div class="form-group my-3">        
                        <label for="inputTitle" class="col-sm-2 col-form-label">Title</label>
                        <input v-model="title" class="form-control" type="text" placeholder="MySuperbTimetable" name="title" id="inputTitle"/>
                    </div>
                    <div class="form-check">
                        <input v-model="isPublic" class="form-check-input" type="checkbox" name="isPublic" id="inputIsPublic"/>
                        <label for="inputIsPublic" class="form-check-label">Public</label>
                    </div>
                    <p>
                        <button type="submit" class="btn btn-outline-primary my-3">Create</button>
                        <button type="submit" class="btn btn-outline-primary my-3 mx-3">Cancel</button>
                    </p>
                </form>
            </div>
        </div>
    </div>
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
