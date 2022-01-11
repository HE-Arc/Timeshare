<template>
    <div>
        <h2>Share ownership</h2>
    </div>

    <form v-on:submit.prevent="onSubmit" method="POST">
        <input type="hidden" name="_token" :value="csrf">

        <div class="row mt-3">
            <div class="col">
                <input class="form-control" type="email" v-model="currentEmail" placeholder="E-mail" name="userEmail" id="inputUserEmail"/>
                <small class="text-muted">E-mail adress of a future Timeholder</small>
            </div>

            <div class="col">
               <button type="submit" class="btn btn-primary">+</button>
            </div>

        </div>
    </form>
</template>


<script>
import { defineComponent } from 'vue'
import { Inertia } from '@inertiajs/inertia'

export default defineComponent({
    props: [
        'timetableId'
    ],
    data() {
        return {
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            currentEmail: ""
        }
    },
    methods:{
        onSubmit(){
            console.log(this.currentEmail, this.timetableId)
            axios.post(route("managements.store", {mail:this.currentEmail, timetable:this.timetableId}))
                        .then(setTimeout(() => this.$parent.updateManagementsList(this.timetableId), 750))
                        .catch(err => console.log(err.response.data));


        }
    }
})
</script>
