<template>
    <div class="w-full">
        <section class="output shadow-sm mt-2 border text-red-900 bg-red-100 p-4" v-if="value.code_executed && !value.valid_output">
            <p class="font-semibold text-red-900">
                Invalid Output Format
            </p>
            <p class="text-red-900" v-if="chartType == 'line_chart'">
                For line charts, output (in <span class="font-semibold">$result</span> var) must be an associative array,
                with dates as keys, and numbers as values.
                <br>Example:
            <pre>[
    "2020-02-21" => 12,
    "2020-02-22" => 14
]</pre></p>
            <p class="text-red-900" v-else-if="chartType == 'bar_chart'">
                For bar charts, output (in <span class="font-semibold">$result</span> var) must be an associative array,
                with column name as keys, and numbers as values.
                <br>Example:
            <pre>[
    "facebook" => 12,
    "twitter" => 14
]</pre>

            </p>
        </section>
        <section class="output shadow-sm mt-2 border"
                 :class="value.code_executed?['text-blue-900 bg-blue-100']:['text-red-900 bg-red-100']">
            <p class="p-4 pb-0 font-semibold" :class="value.code_executed?['text-blue-900']:['text-red-900']">
                Code Execution Result
            </p>
            <pre class="p-4 pt-0 overflow-x-scroll"><code v-html="value.output"></code></pre>
        </section>
    </div>
</template>

<script>
export default {
    props: ['value', 'chartType'],
};
</script>
