<template>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <inertia-link href="/" class="navbar-brand">{{ $page.app.name }}</inertia-link>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <inertia-link href="/" class="nav-item nav-link">Home</inertia-link>
                <inertia-link href="/about" class="nav-item nav-link">About</inertia-link>
                <inertia-link href="/contact" class="nav-item nav-link">Contact</inertia-link>
                <inertia-link href="/users" class="nav-item nav-link">Users</inertia-link>
                <inertia-link v-if="isAuth()" href="/emails" class="nav-item nav-link">Emails</inertia-link>
            </div>
        </div>
        <template v-if="isAuth()">
            <div class="collapse navbar-collapse">
                <div class="navbar-nav">
                    <inertia-link href="/" class="nav-item nav-link">{{ $page.auth.user.name }}</inertia-link>
                    <inertia-link href="/logout" class="nav-item nav-link">Logout</inertia-link>
                </div>
            </div>
        </template>
        <template v-else>
            <div class="collapse navbar-collapse">
                <div class="navbar-nav">
                    <inertia-link href="/auth" class="nav-item nav-link">Login</inertia-link>
                </div>
            </div>
        </template>
    </nav>
</template>

<script>
    export default {
        mounted() {
            //
        },
        methods: {
            isAuth() {
                return Object.keys(this.$page.auth).length > 0 && Object.keys(this.$page.auth.user).length > 0
            }
        }
    }
</script>
