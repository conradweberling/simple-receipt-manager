<template>

    <div>

        <b-alert :show="showAlert" :variant="alert.variant">{{ alert.text }}</b-alert>

        <form ref="form" :action="action" method="post">

            <input name="_token" :value="csrfToken" hidden>

            <div class="form-group row">

                <label for="password" class="col-md-3 col-form-label text-lg-right">Password</label>

                <div class="col-md-8">

                    <input
                        id="password"
                        ref="password"
                        type="password"
                        :class="passwordClass"
                        name="password"
                        required
                        autocomplete="current-password"
                        v-model="passwordValue"
                        @keydown="passwordCorrect=null"
                    >

                    <span v-if="incorrectPassword" class="invalid-feedback" role="alert">
                        <strong>Password not correct</strong>
                    </span>

                </div>

            </div>

            <div class="form-group row mb-0">

                <div class="col-md-8 offset-md-3">
                    <b-button @click="checkPasswordAndSubmit()" variant="danger" :disabled="submitDisabled">
                        <b-spinner v-if="loading" class="mr-2" small></b-spinner>
                        Delete account permanently
                    </b-button>
                </div>

            </div>

        </form>

    </div>

</template>

<script>
export default {
    props: {
        action: {
            required: true
        },
        check: {
            required: true
        }
    },
    data() {
        return {
            passwordValue: '',
            passwordCorrect: null,
            loading: false,
            alert: {
                text: '',
                variant: 'danger'
            }
        }
    },
    methods: {

        checkPasswordAndSubmit() {

            this.loading = true;
            let self = this;

            axios.post(this.check, {

                _token: this.csrfToken,
                password: this.passwordValue

            })
            .then((response) => {

                self.passwordCorrect = !!(response.data.success);
                if(self.passwordCorrect) {
                    self.$refs.form.submit();
                } else {
                    this.loading = false;
                    self.$refs.password.focus();
                }

            })
            .catch(() => {

                this.alert.text = 'An error has occurred!';
                this.loading = false;

            });

        },

        getMetaTag(metaName) {

            let metas = document.getElementsByTagName('meta');

            for (let i = 0; i < metas.length; i++) {
                if (metas[i].getAttribute(  'name') === metaName) {
                    return metas[i].getAttribute('content');
                }
            }

            return '';

        }

    },
    computed: {
        csrfToken() {
            return this.getMetaTag('csrf-token');
        },
        incorrectPassword() {
            return this.passwordCorrect === false;
        },
        passwordClass() {
            return 'form-control '+((this.passwordCorrect === false) ? 'is-invalid' : '');
        },
        showAlert() {
            return this.alert.text.length;
        },
        submitDisabled() {
            return !this.passwordValue.length || this.loading;
        }
    }

}

</script>
