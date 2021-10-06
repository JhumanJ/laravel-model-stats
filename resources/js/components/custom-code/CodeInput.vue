<template>
    <div class="custom-code">
        <label v-if="label" class="text-gray-700 dark:text-gray-300 uppercase font-bold text-xs">
            {{ label }} - <button @click="executeCode" class="bg-blue-500 text-white text-xs p-1 rounded hover:bg-blue-400 pointer">Run Code</button>
        </label>
        <section class="border shadow-sm mt-1 relative">
            <textarea ref="codeEditor" class="z-0"/>
            <div class="pl-8 border-t bg-blue-100 bg-opacity-70 text-blue-900 absolute inset-x-0 bottom-0 text-sm backdrop-filter backdrop-blur-sm z-20">
                Make sure to put your resulting data in a variable named <span class="font-semibold">$result</span>.<br>You can use the variables <span class="font-semibold">$dateFrom</span> and <span class="font-semibold">$dateTo</span> anywhere in your code.
            </div>
        </section>
        <div v-if="error" class="text-sm text-red-500 -bottom-3"
             v-html="error"></div>
    </div>

</template>

<script>
import 'codemirror/mode/php/php';
import CodeMirror from 'codemirror';
import axios from 'axios';

export default {
    data: () => ({
        value: '',
        codeEditor: null,
        error: null,
    }),
    props: ['path', 'label'],
    mounted() {
        const config = {
            autofocus: true,
            extraKeys: {
                'Cmd-Enter': () => {
                    this.executeCode();
                },
                'Ctrl-Enter': () => {
                    this.executeCode();
                },
            },
            indentWithTabs: true,
            lineNumbers: true,
            lineWrapping: true,
            mode: 'text/x-php',
            tabSize: 4,
        };
        this.codeEditor = CodeMirror.fromTextArea(this.$refs.codeEditor, config);
        this.codeEditor.on('change', editor => {
            localStorage.setItem('tinker-tool', editor.getValue());
        });
        let value = localStorage.getItem('tinker-tool');
        if (typeof value === 'string') {
            this.codeEditor.setValue(value);
            this.codeEditor.execCommand('goDocEnd');
        }
    },
    methods: {
        executeCode() {
            this.error = null
            let code = this.codeEditor.getValue().trim();
            if (code === '') {
                this.error = 'You must type some code to execute.'
                return;
            }
            this.$emit('execute', code);
        },
    },
};
</script>

<style src="codemirror/lib/codemirror.css"/>
<style src="codemirror/theme/idea.css"/>
