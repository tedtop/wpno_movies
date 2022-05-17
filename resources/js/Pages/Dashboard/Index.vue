<template>
  <div>
    <Head title="Dashboard" />
    <h1 class="mb-8 text-3xl font-bold">Dashboard</h1>
    <Link class="btn-indigo" href="/movies">
      <span>Go to Movies</span>
    </Link>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3'
import Layout from '@/Shared/Layout'

export default {
  components: {
    Head,
    Link,
  },
  layout: Layout,
}
</script>
