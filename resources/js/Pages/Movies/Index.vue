<template>
  <div>
    <Head title="Movies" />
    <h1 class="mb-8 text-3xl font-bold">Movies</h1>
    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block text-gray-700">Genre:</label>
        <select v-model="form.genre" class="form-select mt-1 w-full">
          <option :value="null" />
          <option>Action</option>
          <option>Biography</option>
          <option>Crime</option>
        </select>
        <label class="block mt-4 text-gray-700">Language:</label>
        <select v-model="form.language" class="form-select mt-1 w-full">
          <option :value="null" />
          <option>English</option>
          <option>Spanish</option>
        </select>
      </search-filter>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-1 pt-6 px-6">Title</th>
          <th class="pb-3 pt-6 px-6">Title</th>
          <th class="pb-4 pt-6 px-6">Genre</th>
          <th class="pb-4 pt-6 px-6" colspan="2">Language</th>
        </tr>
        <tr v-for="movie in movies" :key="movie.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
        <td class="border-t">
            <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/movies/${movie.id}/detail`">
            <img v-if="movie.Poster !== 'N/A'" class="block -my-2 w-20 h-20" :src="movie.Poster" />
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/movies/${movie.id}/detail`">
            <span class="font-bold">{{ movie.Title }}</span>
            <icon v-if="movie.deleted_at" name="trash" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" />
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/movies/${movie.id}/detail`" tabindex="-1">
            {{ movie.Genre }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/movies/${movie.id}/detail`" tabindex="-1">
            {{ movie.Language }}
            </Link>
          </td>
          <td class="w-px border-t">
            <Link class="flex items-center px-4" :href="`/movies/${movie.id}/detail`" tabindex="-1">
            <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </Link>
          </td>
        </tr>
        <tr v-if="movies.length === 0">
          <td class="px-6 py-4 border-t" colspan="4">No movies found.</td>
        </tr>
      </table>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3'
import Icon from '@/Shared/Icon'
import pickBy from 'lodash/pickBy'
import Layout from '@/Shared/Layout'
import throttle from 'lodash/throttle'
import mapValues from 'lodash/mapValues'
import SearchFilter from '@/Shared/SearchFilter'

export default {
  components: {
    Head,
    Icon,
    Link,
    SearchFilter,
  },
  layout: Layout,
  props: {
    filters: Object,
    movies: Array,
  },
  data() {
    return {
      form: {
        search: this.filters.search,
        genre: this.filters.genre,
        language: this.filters.language,
      },
    }
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function () {
        this.$inertia.get('/movies', pickBy(this.form), { preserveState: true })
      }, 500),
    },
  },
  methods: {
    reset() {
      this.form = mapValues(this.form, () => null)
    },
  },
}
</script>
