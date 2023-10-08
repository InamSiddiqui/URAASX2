<template>
    <Head title="Register" />

    <GuestLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">User Details</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                    <div class="bg-green-100 border-t-4 border-green-500 rounded-b text-green-900 px-4 py-3 shadow-md my-3" role="alert" v-if="$page.props.flash.success">
                        <div class="flex">
                            <div>
                                <p class="text-sm">{{ $page.props.flash.success }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-red-100 border-t-4 border-red-500 rounded-b text-red-900 px-4 py-3 shadow-md my-3" role="alert" v-if="$page.props.flash.error">
                        <div >
                            <!--
                            <div>
                                <p v-if="$page.props.flash.error" class="text-sm">{{ $page.props.flash.error }}</p>
                            </div>
                            -->

                            <div v-if="$page.props.flash.error" v-for="(flashErr, flashErrKey) in $page.props.flash.error">
                                <p class="text-sm">{{ flashErr[0] }}</p>
                            </div>


                        </div>
                    </div>               

        <form @submit.prevent="register" enctype="multipart/form-data">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="">

                            <div class="mb-4">
                                <label for="fname"
                                       class="block text-gray-700 text-sm font-bold mb-2">First Name:</label>
                                <input type="text"
                                       v-model="fname"
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                       id="fname" placeholder="First Name">
                                <div v-if="$page.props.errors.fname" class="text-red-500">{{ $page.props.errors.fname }}</div>

                            </div>

                            <div class="mb-4">
                                <label for="lname"
                                       class="block text-gray-700 text-sm font-bold mb-2">Last Name:</label>
                                <input type="text"
                                       v-model="lname"
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                       id="lname" placeholder="Last Name">
                                <div v-if="$page.props.errors.lname" class="text-red-500">{{ $page.props.errors.lname }}</div>

                            </div>

                            <input id="avatar"
            name="avatar"
            type="file"
            accept="image/*"
            class="hidden mt-1 block w-full"
            @input="avatar = $event.target.files[0]" />

            <label for="avatar" class="block text-gray-700 text-sm font-bold mb-2">Change photo</label>



                        </div>
                    </div>

                    
                    <div class="p-6 text-center">

                        

                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Register</button>

                    </div>

                </form>




                </div>

            </div>
        </div>
<!--  -->   


    </GuestLayout>
</template>


<script>
    import GuestLayout from '@/Layouts/GuestLayout.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';

    export default{
        components: {
            GuestLayout,
        },
        data()
        {
            return {
                processing: false,
                avatar:'',
                fname:'',
                lname:'',
            }
        },

        methods: {
            register()
            {   
                this.processing = true;

                let url = '/users';
                this.$inertia.post('register', {
                    photo:this.avatar,
                    fname:this.fname,
                    lname:this.lname
                }, {
                    onError: () => {

                    },
                    onSuccess: () => {

                    }
                });
            },

            changeStatus(id, status)
            {
                this.$inertia.post(route('user.changestatus', {user:id}), {status:status}, {
                    onError: () => {

                    },
                    onSuccess: () => {

                    }
                });
            },

            changePhoto(e){
                this.$inertia.post(route('user.changephoto'), {photo:this.avatar}, {
                    onError: () => {

                    },
                    onSuccess: (res) => {
                        //console.log(res.props.user.photo);
                        this.formObject.photo = res.props.user.photo;
                    }
                });

            },

            uploadDocument(e){                
                this.$inertia.post(route('documents.store'), {documents:this.documents}, {
                    onError: () => {

                    },
                    onSuccess: () => {

                    }
                });
                
            },

        }
    }
</script>