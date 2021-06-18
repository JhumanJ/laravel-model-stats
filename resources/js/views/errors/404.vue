<template>
<div class="bg-white p-6 shadow text-center max-w-md mx-auto">
    <h3 class="text-2xl font-semibold">404</h3>
    <p>Page was not found.
        <router-link :to="{name:'welcome'}">Go to homepage.</router-link>
    </p>
</div>
</template>

<script>
export default {
    name: 'NotFound'
}
</script>

