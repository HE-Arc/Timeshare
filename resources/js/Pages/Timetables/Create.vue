<template>
    <p>Create Timetable</p>
    <form :action="route('timetables.store')" method="POST">
        <input type="hidden" name="_token" :value="csrf">
        <p>
            <label for="inputIsPublic">Public</label>
            <input type="checkbox" name="isPublic" id="inputIsPublic"/>
            <input type='hidden' value='0' name='isPublic'>
        </p>

        <p>
            <label for="inputTitle">Titre</label>
            <input type="text" name="title" id="inputTitle"/>
        </p>

        <p><button type="submit" class="btn btn-primary mt-3">Envoyer</button></p>
    </form>
</template>


<script>
import { defineComponent } from 'vue'

export default defineComponent({
    props: [

    ],
    data() {
        return {
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    }
})
</script>
