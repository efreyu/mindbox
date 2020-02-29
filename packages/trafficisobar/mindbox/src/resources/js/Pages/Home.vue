<template>
    <layout>
        <div class="container mt-lg-2 wrap">
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
                        <button type="button" class="d-inline-flex btn btn-primary" :disabled="buttons.task1.loading" v-on:click="taskOne()">
                            <spinner v-if="buttons.task1.loading" />
                            {{ buttons.task1.text }}
                        </button>
                        <button type="button" class="d-inline-flex btn btn-primary" :disabled="buttons.task2.loading" v-on:click="taskTwo()">
                            <spinner v-if="buttons.task2.loading" />
                            {{ buttons.task2.text }}
                        </button>
                        <button type="button" class="d-inline-flex btn btn-primary" :disabled="buttons.task3.loading" v-on:click="taskThree()">
                            <spinner v-if="buttons.task3.loading" />
                            {{ buttons.task3.text }}
                        </button>
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
    import Spinner from './../Shared/SpinnerComponent'
    import axios from 'axios'

    export default {
        props: ['errors', 'successMessage'],
        components: {
            Layout,
            Spinner,
        },
        data() {
            return {
                buttons: {
                    task1: {
                        loading: false,
                        text: 'Кнопка 1',
                    },
                    task2: {
                        loading: false,
                        text: 'Кнопка 2',
                    },
                    task3: {
                        loading: false,
                        text: 'Кнопка 3',
                    },
                    loadingText: 'Подождите...',
                }
            }
        },
        methods: {
            isAuth() {
                return Object.keys(this.$page.auth).length > 0 && Object.keys(this.$page.auth.user).length > 0
            },
            taskOne() {
                this.apiTask('task1', '/api/v1/action/task1')
            },
            taskTwo() {
                if (!this.isAuth()) {
                    return false;
                }
                this.mindboxTask('task2')
            },
            taskThree() {
                this.apiTask('task3', '/api/v1/action/task3')
            },
            apiTask(task, url)  {
                let btnText = this.buttons[task].text;
                this.buttons[task].text = this.buttons.loadingText;
                this.buttons[task].loading = true;

                axios.get(url).then((response) => {
                    this.buttons[task].text = btnText;
                    this.buttons[task].loading = false;
                })

            },
            mindboxTask(task) {
                let btnText = this.buttons[task].text;
                this.buttons[task].text = this.buttons.loadingText;
                this.buttons[task].loading = true;

                mindbox("async", {
                    operation: "Jti.v3.TestAction2",
                    data: {
                        customer: {
                            ids: {
                                mindboxId: this.$page.auth.user.id
                            }
                        }
                    }
                });

                setTimeout(() => {
                    this.buttons[task].text = btnText;
                    this.buttons[task].loading = false;
                }, 1500)
            },
        }
    }
</script>

<style>
    .wrap {
        padding-top: 50px;
    }
</style>
