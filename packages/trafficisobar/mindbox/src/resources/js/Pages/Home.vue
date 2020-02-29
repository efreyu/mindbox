<template>
    <layout>
        <div class="container mt-lg-2">
            <h1>Welcome to Mindbox Home Page</h1>
            <div v-if="Object.keys(errors).length > 0" class="alert alert-danger mt-4">
                <div v-for="error in errors">
                    <span>{{ error[0] }}</span>
                </div>
            </div>
            <div v-if="successMessage" class="alert alert-success mt-4">
                {{ successMessage }}
            </div>

            <template v-if="isAuth()">
                <div class="media">
                    <div class="media-body">
                        Тут будут кнопки
                    </div>
                </div>
            </template>
            <template v-else>
                <div class="alert alert-primary" role="alert">
                    Для получения доступа Вам необходимо <inertia-link href="/auth">авторизоваться</inertia-link>.
                </div>

            </template>
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
        methods: {
            isAuth() {
                return Object.keys(this.$page.auth).length > 0 && Object.keys(this.$page.auth.user).length > 0
            }
        }
    }
</script>
