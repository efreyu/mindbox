<template>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container mt-lg-2">
            <div class="navbar-nav">
                <inertia-link href="/" class="navbar-brand">{{ $page.app.name }}</inertia-link>
                <inertia-link href="/" class="nav-item nav-link">Home</inertia-link>
            </div>
            <template v-if="isAuth()">
                <div class="navbar-nav">
                    <inertia-link href="/" class="nav-item nav-link">{{ $page.auth.user.name }}</inertia-link>
                    <inertia-link href="/logout" class="nav-item nav-link">Logout</inertia-link>
                </div>
            </template>
            <template v-else>
                <div class="navbar-nav">
                    <inertia-link href="/auth" class="nav-item nav-link">Login</inertia-link>
                </div>
            </template>
        </div>
    </nav>
</template>

<script>
    export default {
        methods: {
            isAuth() {
                return Object.keys(this.$page.auth).length > 0 && Object.keys(this.$page.auth.user).length > 0
            }
        }
    }
</script>
