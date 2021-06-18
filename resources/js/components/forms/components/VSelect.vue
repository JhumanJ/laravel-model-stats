<template>
    <div class="space-y-1">
        <label v-if="label" class="text-gray-700 dark:text-gray-300 uppercase font-bold text-xs">
            {{ label }}
            <span v-if="required" class="text-red-500 required-dot">*</span>
        </label>

        <div v-on-clickaway="closeDropdown"
             class="relative"
        >
      <span class="inline-block w-full shadow-sm">
        <button type="button" aria-haspopup="listbox" aria-expanded="true" aria-labelledby="listbox-label"
                class="cursor-pointer relative w-full border-transparent flex-1 appearance-none border border-gray-300 w-full py-2 px-4 bg-white text-gray-700 placeholder-gray-400 dark:bg-notion-dark-light dark:text-gray-300 dark:placeholder-gray-600 shadow-sm text-base focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                @click="openDropdown"
        >
          <div class="h-6">
            <slot v-if="value" name="selected" :option="value"/>
          </div>
          <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="none" stroke="currentColor">
              <path d="M7 7l3-3 3 3m0 6l-3 3-3-3" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </span>
        </button>
      </span>

            <!-- Select popover, show/hide based on select state. -->
            <div v-show="isOpen" class="absolute mt-1 w-full bg-white shadow-lg z-10">
                <ul class="max-h-40 py-1 leading-6 shadow-xs overflow-auto focus:outline-none sm:text-sm sm:leading-5">
                    <li v-for="item in data" :key="item[optionKey]" tabindex="0" role="option"
                        class="text-gray-900 cursor-default select-none relative py-2 pl-3 pr-9 cursor-pointer hover:text-white hover:bg-blue-500 focus:outline-none focus:text-white focus:bg-blue-500"
                        @click="select(item)" :class="{'text-white bg-blue-500':isSelected(item)}"
                    >
                        <slot name="option" :option="item" :selected="isSelected(item)"/>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
import { directive as onClickaway } from 'vue-clickaway'

export default {
    name: 'VSelect',
    directives: {
        onClickaway: onClickaway
    },
    props: {
        data: Array,
        value: { default: null },
        label: { type: String, default: null },
        required: { type: Boolean, default: false },
        multiple: { type: Boolean, default: false },
        optionKey: { type: String, default: 'id' },
        emitKey: { type: String, default: null } // Key used for emitted value, emit object if null
    },
    data () {
        return {
            isOpen: false
        }
    },
    methods: {
        isSelected (value) {
            if (!this.value) return false

            // Get the actual value
            if (this.emitKey && value[this.emitKey]) {
                value = value[this.emitKey]
            } else if (typeof value == 'object') {
                value = value[this.optionKey]
            }

            if (this.multiple) {
                return this.value.includes(value)
            }

            if (typeof this.value == 'object') {
                return this.value[this.optionKey] === value
            }

            return this.value === value
        },
        closeDropdown () {
            this.isOpen = false
        },
        openDropdown () {
            this.isOpen = true
        },
        select (value) {
            this.isOpen = false

            if (this.emitKey) {
                value = value[this.emitKey]
            }

            if (this.multiple) {
                const emitValue = Array.isArray(this.value) ? [...this.value] : []

                // Already in value, remove it
                if (this.isSelected(value)) {
                    this.$emit('input', emitValue.filter((item) => {
                        if (this.emitKey) {
                            return item !== value
                        }
                        return item[this.optionKey] !== value && item[this.optionKey] !== value[this.optionKey]
                    }))
                    return
                }

                // Otherwise add value
                emitValue.push(value)
                this.$emit('input', emitValue)
            } else {
                this.$emit('input', value)
            }
        }
    }
}
</script>
