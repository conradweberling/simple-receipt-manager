<template>

    <div>

        <b-alert :show="showAlert" :variant="alert.variant" dismissible>{{ alert.text }}</b-alert>

        <div class="form-group row">

            <label for="nemail" class="col-md-4 col-form-label text-lg-right">New E-Mail</label>

            <div class="col-md-6">

                <input
                    id="nemail"
                    type="email"
                    name="nemail"
                    :class="emailClass"
                    required
                    v-model="emailValue"
                    @change="checkEmail()"
                >

                <span v-if="incorrectEmail" class="invalid-feedback" role="alert">
                        <strong>The E-Mail is invalid.</strong>
                </span>

                <span v-if="emailExists === false" class="valid-feedback" role="alert">
                        <strong>The E-Mail is available.</strong>
                </span>

                <span v-if="emailExists" class="invalid-feedback" role="alert">
                        <strong>The E-Mail has already been taken.</strong>
                </span>

            </div>

        </div>

        <div class="form-group row">

                <label for="cnemail" class="col-md-4 col-form-label text-lg-right">Confirm New E-Mail</label>

                <div class="col-md-6">

                    <input
                        id="cnemail"
                        type="email"
                        name="cnemail"
                        :class="emailConfirmClass"
                        required
                        v-model="emailConfirmValue"
                    >

                    <span v-if="!emailConfirmMatch" class="invalid-feedback" role="alert">
                        <strong>The emails do not match.</strong>
                    </span>

                </div>

        </div>

        <hr class="my-4">

        <div class="form-group row mb-0">

            <div class="col-md-8 offset-md-4">
                <b-overlay :show="loading" class="d-inline-block" spinner-small>
                    <b-button
                        ref="button"
                        variant="primary"
                        :disabled="!formValid"
                        @click="updateEmail()"
                    >
                        Change E-Mail
                    </b-button>
                </b-overlay>
            </div>

        </div>


    </div>

</template>

<script>

    export default {
        props: {
            update: {
                required: true
            },
            check: {
                required: true
            }
        },
        data() {
            return {
                emailValue: "",
                emailConfirmValue: "",
                emailExists: null,
                emailConfirmMatch: null,
                loading: false,
                alert: {
                    text: '',
                    variant: 'danger'
                },
                reg: /^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)$/
            }
        },
        methods: {
            checkEmail() {

                if(this.incorrectEmail) return false;

                let self = this;
                axios.post(this.check, {
                    email: this.emailValue
                })
                    .then((response) => {

                        self.emailExists = response.data.exists;

                    })
                    .catch(() => {

                        self.emailExists = false;

                    });


            },
            updateEmail() {
                this.loading = true;
                let self = this;

                axios.post(this.update, {
                    email: this.emailValue,
                    email_confirmation: this.emailConfirmValue
                })
                    .then((response) => {

                        self.alert.text = 'E-Mail changed successfully.';
                        self.alert.variant = 'success';
                        self.loading = false;
                        self.resetForm();

                    })
                    .catch(() => {

                        self.alert.text = 'An error has occurred!';
                        self.loading = false;

                    });
            },
            validateEmailConfirm() {
                this.emailConfirmMatch = this.emailValue === this.emailConfirmValue;
            },
            resetForm() {
                this.emailValue = "";
                this.emailConfirmValue = "";
                this.emailConfirmMatch = null;
                this.emailExists = null;
            }
        },
        computed: {
            formValid() {
                return this.reg.test(this.emailValue) && this.emailConfirmMatch && this.emailExists===false;
            },
            emailClass() {
                return  "form-control"+((this.incorrectEmail || this.emailExists) ? " is-invalid" : "")+
                        ((this.emailExists === false) ? ' is-valid' : '')
            },
            emailConfirmClass() {
                return  'form-control'+((this.emailConfirmMatch === false) ? ' is-invalid' : '');
            },
            incorrectEmail() {
                return this.emailValue.length && !this.reg.test(this.emailValue);
            },
            showAlert() {
                return this.alert.text.length;
            }
        },
        watch: {
            emailValue: {
                handler: function() {
                    if(this.emailConfirmValue.length) this.validateEmailConfirm();
                }
            },
            emailConfirmValue: {
                handler: function() {
                    this.validateEmailConfirm();
                }
            }
        }
    }

</script>
