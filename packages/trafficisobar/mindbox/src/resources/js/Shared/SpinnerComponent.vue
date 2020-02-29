<template>
    <atom-spinner
        :animation-duration="1000"
        :size="20"
        color="#fff"
        class="mr-2"
    />
</template>

<script>
    import { AtomSpinner } from 'epic-spinners'
    export default {
        components: {
            AtomSpinner
        },
        mounted() {
            // console.log('spiner mounted');
        }
    }
</script>
