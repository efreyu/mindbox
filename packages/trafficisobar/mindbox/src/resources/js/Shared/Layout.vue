<template>
    <main>
        <main-menu />

        <article>
            <slot />
        </article>
    </main>
</template>

<script>
    import MainMenu from './MainMenu'

    export default {
        components: {
            MainMenu,
        }
    }
</script>
