<template>
  <div class="container">
    <div class="row justify-content-center my-5">
      <div class="col-sm-12 col-md-8 col-lg-5 my-5">
        <div class="d-flex justify-content-center mb-3">
          <Link href="/" class="text-decoration-none">
            <!--<breeze-application-logo width="82" />-->
            <h1 class="display-3 app-logo-font">Timeshare</h1>
          </Link>
        </div>

        <div class="card shadow-sm px-3">
          <slot />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import BreezeApplicationLogo from '@/Components/ApplicationLogo.vue'
import { Link } from '@inertiajs/inertia-vue3';

export default {
  components: {
    BreezeApplicationLogo,
    Link,
  }
}
</script>
