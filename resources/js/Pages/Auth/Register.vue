<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    fname: '',
    lname: '',
    email: '',
    password: '',
    password_confirmation: '',
    photo: '',
    documents: [],
});

const submit = () => {
    let isOk = true;
    form.errors.fname = form.errors.lname = form.errors.email = form.errors.password = form.errors.password_confirmation = '';

    if(form.fname.length <= 2){
        form.errors.fname = "First name must have at least 3 character";
        isOk = false;
    }
    if(form.lname.length <= 2){
        form.errors.lname = "First name must have at least 3 character";
        isOk = false;
    }
    if(form.email.length == ''){
        form.errors.email = "Please enter a valid email address";
        isOk = false;
    }else{
        if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(form.email)) {
            form.errors.email = "Entered email address is invalid!";
            isOk = false;
        }
    }
    if(form.password.length == ''){
        form.errors.password = "Please enter a password";
        isOk = false;
    }
    if(form.password_confirmation.length == ''){
        form.errors.password_confirmation = "Please enter confirm password";
        isOk = false;
    }
    if(form.password != form.password_confirmation){
        form.errors.password = "The password field confirmation does not match.";
        isOk = false;
    }
    if(form.photo.length == ''){
        form.errors.photo = "Please attach your photo";
        isOk = false;
    }

    if(isOk){
        form.post(route('register'), {
            onFinish: () => form.reset('password', 'password_confirmation'),
        });
    }
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <div class="bg-red-100 border-t-4 border-red-500 rounded-b text-red-900 px-4 py-3 shadow-md my-3" role="alert" v-if="$page.props.flash.servererror">
            <div>
                <p class="text-sm">{{ $page.props.flash.servererror }}</p>
            </div>
        </div>

        <form @submit.prevent="submit">
            <!--
            <div>
                <InputLabel for="name" value="Name" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>
            -->

            <div class="mt-4">
                <InputLabel for="fname" value="First Name*" />

                <TextInput
                    id="fname"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.fname"
                    
                    autofocus
                    autocomplete="fname"
                />

                <InputError class="mt-2" :message="form.errors.fname" />
            </div>

            <div class="mt-4">
                <InputLabel for="lname" value="Last Name*" />

                <TextInput
                    id="lname"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.lname"
                    
                    autofocus
                    autocomplete="lname"
                />

                <InputError class="mt-2" :message="form.errors.lname" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Email*" />

                <TextInput
                    id="email"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password*" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel for="password_confirmation" value="Confirm Password" />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div class="mt-4">
                <InputLabel for="photo" value="Upload Photo*" />
                <small>Maximum photo size 1MB, and Allowed formats: jpg,jpeg,png </small>
                <TextInput
                    id="photo"
                    type="file"
                    class="mt-1 block w-full"
                    
                    accept="image/*"
                    @input="form.photo = $event.target.files[0]"
                />

                <InputError class="mt-2" :message="form.errors.photo" />
            </div>

            <div class="mt-6">
                <InputLabel for="documents" value="Upload Document(s)" />
                <small>Each file should be less than 2MB</small>
                <TextInput
                    id="documents"
                    type="file"
                    class="mt-1 block w-full"
                    
                    multiple="multiple"
                    @input="form.documents = $event.target.files"
                />

                <div class="mt-2" v-if="form.errors" v-for="(docErr, docErrKey) in form.errors">
                    <p v-if="docErrKey.includes('documents')" class="text-sm text-red-600">{{ docErr }}</p>
                </div>

            </div>     


            <div class="flex items-center justify-end mt-4">
                <Link
                    :href="route('login')"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Already registered?
                </Link>

                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Register
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
