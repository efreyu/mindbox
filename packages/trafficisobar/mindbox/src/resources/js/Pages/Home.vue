<template>
    <layout>
        <div>
            test 123
        </div>
    </layout>
</template>

<script>
    import Layout from './../Shared/Layout'
    export default {
        props: ['errors', 'successMessage'],
        components: {
            Layout,
        },
        mounted() {
            //
        }
    }
</script>
