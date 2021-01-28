<template>

    <div>

        <b-alert :show="showAlert" :variant="alert.variant" dismissible>{{ alert.text }}</b-alert>

        <div class="form-group row">

            <label for="rpassword" class="col-md-4 col-form-label text-lg-right">Current Password</label>

            <div class="col-md-6">

                <input
                    id="rpassword"
                    type="password"
                    :class="passwordClass"
                    name="rpassword"
                    required
                    autocomplete="current-password"
                    v-model="password"
                    @change="checkPassword()"
                />

                <span v-if="incorrectPassword" class="invalid-feedback" role="alert">
                    <strong>Password not correct.</strong>
                </span>

                <span v-if="correctPassword" class="valid-feedback" role="alert">
                    <strong>Password correct.</strong>
                </span>

            </div>

        </div>

        <div class="form-group row">

            <label for="new_password" class="col-md-4 col-form-label text-lg-right">New Password</label>

            <div class="col-md-6">

                <input
                    id="new_password"
                    type="password"
                    :class="newPasswordClass"
                    name="new_password"
                    autocomplete="current-password"
                    v-model="newPassword"
                    @change="validateNewPassword()"
                />

                <span v-if="!newPasswordMinLength" class="invalid-feedback" role="alert">
                    <strong>
                        The password must be at least 8 characters.
                    </strong>
                </span>

            </div>

        </div>

        <div class="form-group row">

            <label for="new_password_confirmation" class="col-md-4 col-form-label text-lg-right">Confirm New Password</label>

            <div class="col-md-6">

                <input
                    id="new_password_confirmation"
                    type="password"
                    :class="newPasswordConfirmationClass"
                    name="new_password_confirmation"
                    autocomplete="current-password"
                    v-model="newPasswordConfirmation"
                />

                <span v-if="!newPasswordConfirmationMatch" class="invalid-feedback" role="alert">
                    <strong>
                        The passwords do not match.
                    </strong>
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
                        @click="updatePassword()"
                    >
                        Change Password
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
                required: true,
            }
        },
        data() {
            return {
                passwordCheckResponse: null,
                password: "",
                newPassword: "",
                newPasswordMinLength: null,
                newPasswordConfirmation: "",
                newPasswordConfirmationMatch: null,
                loading: false,
                alert: {
                    text: '',
                    variant: 'danger'
                }
            }
        },
        methods: {
            checkPassword() {

                let self = this;

                axios.post(this.check, {
                        password: this.password
                    })
                    .then((response) => {
                        self.passwordCheckResponse = !!(response.data.success);
                    })

            },
            updatePassword() {

                this.loading = true;
                let self = this;

                axios.post(this.update, {
                        password: this.password,
                        new_password: this.newPassword,
                        new_password_confirmation: this.newPasswordConfirmation
                    })
                    .then((response) => {

                        self.alert.text = 'Password changed successfully.';
                        self.alert.variant = 'success';
                        self.loading = false;
                        self.resetForm();

                    })
                    .catch(() => {

                        self.alert.text = 'An error has occurred!';
                        self.loading = false;

                    });


            },
            validateNewPassword() {
                this.newPasswordMinLength = this.newPassword.length > 7;
            },
            validateNewPasswordConfirmation() {
                this.newPasswordConfirmationMatch = this.newPassword === this.newPasswordConfirmation;
            },
            resetForm() {
                this.password = "";
                this.newPassword = "";
                this.newPasswordConfirmation = "";
                this.passwordCheckResponse = null;
            }
        },
        computed: {
            incorrectPassword() {
                return this.passwordCheckResponse === false;
            },
            correctPassword() {
                return this.passwordCheckResponse === true;
            },
            passwordClass() {
                return  'form-control'+((this.incorrectPassword === true) ? ' is-invalid' : '')+
                        ((this.correctPassword === true) ? ' is-valid' : '');
            },
            newPasswordClass() {
                return  'form-control'+((this.newPasswordMinLength === false) ? ' is-invalid' : '')
            },
            newPasswordConfirmationClass() {
                return  'form-control'+((this.newPasswordConfirmationMatch === false) ? ' is-invalid' : '')
            },
            formValid() {
                return this.correctPassword && this.newPasswordMinLength && this.newPasswordConfirmationMatch;
            },
            showAlert() {
                return this.alert.text.length;
            }
        },
        watch: {
            newPassword: {
                handler: function() {
                    if(this.newPasswordConfirmation.length) this.validateNewPasswordConfirmation();
                }
            },
            newPasswordConfirmation: {
                handler: function() {
                    this.validateNewPasswordConfirmation();
                }
            }
        }

    }
</script>
