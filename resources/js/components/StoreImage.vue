<template>

    <div class="form-group row">
        <label for="image-select" class="col-md-4 col-form-label text-md-right">Receipt</label>

        <div class="col-md-6">

            <b-overlay :show="loading" class="d-inline-block w-100" spinner-small>
                <b-form-file
                    id="image-select"
                    ref="imageselect"
                    placeholder="Choose an image..."
                    drop-placeholder="Drop image here..."
                    :state="imageState"
                    accept="image/jpeg, image/png"
                    required
                    autofocus
                    @change="storeImage()"
                ></b-form-file>
            </b-overlay>

            <b-form-invalid-feedback :state="imageState">
                <strong>{{ error }}</strong>
            </b-form-invalid-feedback>
            <b-form-valid-feedback :state="imageState">
                Image uploaded successfully.
            </b-form-valid-feedback>

        </div>

        <input id="image" name="image" :value="imageName" hidden required/>
        <input id="thumbnail" name="thumbnail" :value="thumbnailName" hidden required/>

    </div>

</template>

<script>

export default {
    props: {
        route: {
            required: true
        },
        error: {
            default: ""
        }
    },
    data() {
        return {
            loading: false,
            success: null,
            file: null,
            imageName: "",
            thumbnailName: "",
        }
    },
    methods: {
        storeImage() {

            let self = this;
            let input = this.$refs.imageselect.$refs.input;
            let formData = new FormData();

            this.success = null;
            this.loading = true;
            this.file = input.files[0];
            if(typeof this.file === 'undefined') return false;

            input.blur();

            formData.append('image', this.file);

            axios.post(
                this.route,
                formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then((response) => {
                    self.imageName = response.data.image;
                    self.thumbnailName = response.data.thumbnail;
                    self.success = true;
                })
                .catch(() => {
                    self.success = false;
                    self.error = "Image could not be uploaded!"
                })
                .finally(() => {
                    self.loading = false;
                })

        }

    },
    computed: {

        imageState() {
            return (this.success === true) ? true : (this.success === null && !this.error.length) ? null : false;
        }

    }

}

</script>
