<template>
    <layout>
        <div class="container mt-lg-2">
            <div class="col-md-12">
                <div v-if="Object.keys(errors).length > 0" class="alert alert-danger mt-4">
                    <div v-for="error in errors">
                        <span>{{ error[0] }}</span>
                    </div>
                </div>
                <form action="#" method="post" class="my-5 form-signin"
                      @submit.prevent="loginUser">
                    <h1 class="h3 mb-3 font-weight-normal form-signin-title"> Авторизация</h1>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" placeholder="email" v-model="form.email">
                    </div>
                    <div class="form-group">
                        <label for="password">Пароль</label>
                        <input type="password" class="form-control" id="password" placeholder="пароль" v-model="form.password">
                    </div>
                    <button type="submit" class="d-flex btn btn-primary" :disabled="loading">
                        <spinner v-if="loading" />
                        {{ authButton }}
                    </button>
                </form>
            </div>
        </div>
    </layout>
</template>

<script>
    import Layout from './../../Shared/Layout'
    import Spinner from './../../Shared/SpinnerComponent'

    export default {
        props: ['errors'],
        components: {
            Layout,
            Spinner,
        },
        data() {
            return {
                loading: false,
                authButton: 'Авторизоваться',
                authStay: 'Авторизоваться',
                authProcess: 'Авторизация',
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
                this.authButton = this.authProcess;

                if (!this.form.email || this.form.email.length < 6) {
                    this.errors.email = ['Поле Email обязательно. Минимальная длинна 6 символов.'];
                    passwordCallback = true;
                    this.loading = false;
                    this.authButton = this.authStay;
                }
                if (!this.form.password || this.form.password.length < 6) {
                    this.errors.password = ['Поле Password обязательно. Минимальная длинна 6 символов.'];
                    passwordCallback = true;
                    this.loading = false;
                    this.authButton = this.authStay;
                }

                if (!passwordCallback) {
                    this.$inertia.post(`/login`, this.form)
                        .then((response) => {
                            this.loading = false;
                            this.authButton = this.authStay;
                        })
                }
            }
        }
    }
</script>

<style>
    .form-signin {
        width: 464px;
        margin:10vh auto;
        padding: 20px;
        background-color:#f3f3f3;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
        transition: all 0.3s cubic-bezier(.25,.8,.25,1);
    }
    .form-signin-title {
        text-align: center
    }
</style>
