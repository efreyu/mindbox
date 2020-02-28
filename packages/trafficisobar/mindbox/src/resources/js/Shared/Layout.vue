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
    /*
     * wrapCallback используется для отключения полоски загрузки
     * во время первой инициализации страницы
     */
    let wrapCallback = false
    export default {
        components: {
            MainMenu,
        },
        props: {
            title: String,
        },
        data() {
            return {
                // showUserMenu: false,
                // accounts: null,
            }
        },
        watch: {
            // title(title) {
            //     this.updatePageTitle(title)
            // },
        },
        mounted() {
            // this.updatePageTitle(this.title)
            // this.$Progress.finish()
        },
        created() {
            // if (wrapCallback) this.$Progress.start()
            // if (!wrapCallback) wrapCallback = true
        },
        methods: {
            updatePageTitle(title) {
                document.title = title ? `${title} | ${this.$page.app.name}` : this.$page.app.name
            }
        },
        // inject: ['app', 'auth'],
        // props: ['app', 'auth']
    }
</script>
