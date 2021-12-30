<template>
    <p v-for="management in managements" :key="management.id">
        {{management.email}}
        <button @click="destroy(management.id)" class="btn btn-danger"><i class="bi bi-trash"></i></button>
    </p>
</template>


<script>
import { defineComponent } from 'vue'
import { Inertia } from '@inertiajs/inertia'

export default defineComponent({
    props: [
        'managements'
    ],
    data() {

    },
    methods:{
        destroy(id){
            Inertia.delete(route("managements.destroy", id));
            setTimeout(() => this.$parent.updateManagementsList(), 500);
        }
    }
})
</script>
