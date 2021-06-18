<template>
    <div class="flex flex-col">
        <div class="flex">
            <div class="flex-grow"/>
            <add-widget/>
            <button id="save-dashboard"
                    class="ml-1 bg-white text-gray-500 hover:text-blue-500 p-2 relative shadow-md border hover:bg-gray-100 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path
                        d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V6h5a2 2 0 012 2v7a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2h5v5.586l-1.293-1.293zM9 4a1 1 0 012 0v2H9V4z"/>
                </svg>
            </button>
        </div>
        <p>dashboard</p>
    </div>
</template>

<script>
import AddWidget from '../components/widgets/AddWidget'

export default {
    name: 'Dashboard',
    components: { AddWidget }
}
</script>

