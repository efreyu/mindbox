<template>
    <layout>
        <div class="container">
            <h1>Login Page</h1>
            <div class="col-md-6">
                <div v-if="Object.keys(errors).length > 0" class="alert alert-danger mt-4">
                    <div v-for="error in errors">
                        <span>{{ error[0] }}</span>
                    </div>
                </div>
                <form action="#" method="post" class="my-5"
                      @submit.prevent="loginUser">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" placeholder="email" v-model="form.email">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" placeholder="password" v-model="form.password">
                    </div>
                    <button type="submit" class="d-flex btn btn-primary" :disabled="loading">
<!--                        <spinner v-if="loading" />-->
                        Login
                    </button>
                </form>
            </div>
        </div>
    </layout>
</template>

<script>
    import Layout from './../../Shared/Layout'
    // import Spinner from './../../SpinnerComponent'
    export default {
        props: ['errors'],
        components: {
            Layout,
            // Spinner
        },
        data() {
            return {
                loading: false,
                form: {
                    email: null,
                    password: null,
                }
            }
        },
        methods: {
            loginUser() {
                let passwordCallback = false;
                this.loading = true;

                if (!this.form.email || this.form.email.length < 6) {
                    this.errors.email = ['Поле Email обязательно. Минимальная длинна 6 символов.'];
                    passwordCallback = true;
                }
                if (!this.form.password || this.form.password.length < 6) {
                    this.errors.password = ['Поле Password обязательно. Минимальная длинна 6 символов.'];
                    passwordCallback = true;
                }

                console.log(Object.keys(this.errors).length);
                if (!passwordCallback) {
                    this.$inertia.post(`/login`, this.form)
                        .then((response) => {
                            this.loading = false;
                        })
                }
                this.loading = false;
            }
        }
    }
</script>
