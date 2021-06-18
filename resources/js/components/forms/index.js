import Vue from 'vue'

import HasError from './validation/HasError.vue'
import AlertError from './validation/AlertError'
import AlertSuccess from './validation/AlertSuccess'
import VCheckbox from './components/VCheckbox'
import TextInput from './TextInput'
import VSelect from './components/VSelect'
import CheckboxInput from './CheckboxInput'
import SelectInput from './SelectInput';

// Components that are registered globaly.
[
    HasError,
    AlertError,
    AlertSuccess,
    VCheckbox,
    VSelect,
    CheckboxInput,
    TextInput,
    SelectInput
].forEach(Component => {
    Vue.component(Component.name, Component)
})
