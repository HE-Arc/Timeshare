<template>
  <Head title="Welcome" />

  
  <div class="container-fluid my-5 pt-5 px-5">
    <section class="jumbotron text-center">
      <div class="container-fluid my-5 pt-5 px-5">
        <h1 class="jumbotron-heading">Timeshare</h1>
        <p class="lead text-muted">
          Share your schedule with anyone from friends to coworkers.
        </p>
        
        <div v-if="canLogin">

          <a v-if="$page.props.auth.user"
            href="/timetables">
            <button
              class="btn btn-outline-primary btn-lg mx-2">
              Timetable
            </button>
          </a>

          <template v-else>
            <a :href="route('login')">
              <button
              class="btn btn-outline-primary btn-lg mx-2">
              Sign in
              </button>
            </a>
            
            <a :href="route('register')">
              <button v-if="canRegister"
              class="btn btn-outline-primary btn-lg mx-2">
              Sign up
              </button>
            </a>
          </template>
        </div>
      </div>
    </section>

    <div class="text-center py-5">
      <p class="text-sm text-muted">
        Timeshare HE-Arc 2021-22
      </p>
    </div>
  </div>

</template>

<script>
import { Head, Link } from "@inertiajs/inertia-vue3";
import Button from '@/Components/Button.vue';

export default {
  components: {
    Head,
    Link,
    Button
  },
  props: {
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String
  }
};
</script>
