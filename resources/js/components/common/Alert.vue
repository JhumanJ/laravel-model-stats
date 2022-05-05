<template>
    <div :class="'bg-'+bgColor+'-100 border border-'+bgColor+'-500 shadow-sm p-6 flex'">
        <div class="text-center flex-grow">
            <p class="mt-2 mb-0">{{ message }}</p>
        </div>

        <div class="flex items-end ml-4">
            <div>
            <v-button v-if="type == 'error'" color="red" shade="light" @click="close">
                CLOSE
            </v-button>
            <v-button v-if="type == 'success'" color="green" shade="light" @click="close">
                OK
            </v-button>
            <v-button v-if="type == 'confirmation'" class="mr-1" @click="confirm">
                YES
            </v-button>
            <v-button v-if="type == 'confirmation'" color="gray" shade="light" @click="cancel">
                NO, CANCEL
            </v-button>
            </div>

        </div>

    </div>
</template>

<script>
export default {
    name: 'Alert',
    props: ['type', 'message', 'autoClose', 'confirmationProceed', 'confirmationCancel'],

    data() {
        return {
            timeout: null,
        }
    },

    mounted() {
        if (this.autoClose) {
            this.timeout = setTimeout(() => {
                this.close();
            }, this.autoClose);
        }
    },

    methods: {
        /**
         * Close the modal.
         */
        close() {
            clearTimeout(this.timeout);
            this.$emit('close');
        },
        /**
         * Confirm and close the modal.
         */
        confirm() {
            this.confirmationProceed();
            this.close();
        },
        /**
         * Cancel and close the modal.
         */
        cancel() {
            if (this.confirmationCancel) {
                this.confirmationCancel();
            }
            this.close();
        }
    },

    computed: {
        bgColor() {
            if (this.type == 'error') return 'red';
            if (this.type == 'success') return 'green';
            return 'blue';
        }
    }
}
</script>
