import Vue from 'vue'

import VButton from "./VButton";
import Alert from "./Alert";

// Components that are registered globaly.
[
    Alert,
    VButton
].forEach(Component => {
    Vue.component(Component.name, Component)
})
