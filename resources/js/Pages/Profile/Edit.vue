<template>
    <Head title="User Details" />

    <AuthenticatedLayout>
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
                        <div v-for="(flashErr, flashErrKey) in $page.props.flash.error">
                            <p class="text-sm">{{ flashErr[0] }}</p>
                        </div>
                    </div>
                    <div class="bg-red-100 border-t-4 border-red-500 rounded-b text-red-900 px-4 py-3 shadow-md my-3" role="alert" v-if="$page.props.flash.servererror">
                        <div>
                            <p class="text-sm">{{ $page.props.flash.servererror }}</p>
                        </div>
                    </div>
                    

                    <div class="p-6 text-right">

                        <button @click="openForm(user)" class="ml-5 text-black-600 hover:underline">Edit</button>

                    </div>
                </div>
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
      <div class="mx-auto my-5 bg-white rounded-lg shadow-md p-5">
        <!-- 
        <img class="w-32 h-32 rounded-full mx-auto" src="https://picsum.photos/200" alt="Profile picture">
        -->
        <p class="text-center">
        <form enctype="multipart/form-data">
            <img class="w-32 h-32 rounded-full mx-auto" :src="user.photo" :alt="user.fname+' '+user.lname">

            <input id="avatar"
            name="avatar"
            type="file"
            accept="image/*"
            class="hidden mt-1 block w-full"
            @input="avatar = $event.target.files[0]" @change="changePhoto" />

            <label for="avatar" class="block text-gray-700 text-sm font-bold mb-2">Change photo</label>
        </form>
        </p>


        <h2 class="text-center text-2xl font-semibold mt-3">{{user.fname}} {{user.lname}}</h2>

        <p class="text-center mt-1">
        <a :href="'mailto:'+user.email" class="text-center text-1xl text-blue-600 mt-3">{{user.email}}</a>
        </p>

        <p class="text-center text-gray-600 mt-1" v-if="user.status=='awaiting'">Status: Awaiting for approval</p>

        <p class="text-center text-gray-600 mt-1" v-if="user.status=='approved'">Status: Approved</p>

        <p class="text-center text-gray-600 mt-1" v-if="user.status=='rejected'">Status: Rejected</p>

        <div class="text-center justify-center mt-5">
          <div class="text-gray-600 mx-3">Registeration: {{user.created_at}}</div>

          <div class="text-gray-600 mx-3">Last Updated: {{user.updated_at}}</div>
        </div>

        <div class="m-5">
          <h3 class="text-xl font-semibold">Documents

            <form enctype="multipart/form-data" class="text-right">
                <input id="documents"
                name="documents"
                type="file"
                multiple
                class="hidden mt-1 block w-full"
                @input="documents = $event.target.files" @change="uploadDocument" />

                <label for="documents" class="bg-blue-500 hover:bg-blue-700 text-white text-sm py-1 px-3 rounded" :class="{ 'opacity-25': processing }" :disabled="processing">Upload Document(s)</label>
            </form>


          </h3>

          <div class="text-gray-600 mt-2 mb-2">

<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    File Name
                </th>
                <th scope="col" class="px-6 py-3">
                    Uploaded Time
                </th>
                <th scope="col" class="px-6 py-3">
                    <span class="px-6 py-3">Action</span>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(document, index) in documents" :key="index" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <a :href="document.path" :alt="document.original_name" :download="document.original_name" class="hover:underline">Download</a>

                    <a :href="document.path" :alt="document.original_name" target="_blank" class="ml-5 text-blue-600 dark:text-blue-500 hover:underline">{{document.original_name}}</a>
                </th>
                <td class="px-6 py-4">
                    {{document.created_at}}
                </td>
                <td class="px-6 py-4">
                    <a href="javascript:void(0)" @click="deleteDocument(document.id)" class="font-medium text-red-600 dark:text-red-500 hover:underline" :class="{ 'opacity-25': processing }" :disabled="processing">Delete</a>
                </td>
            </tr>
            <tr v-if="documents.length<=0">
                <td colspan="3" class="font-bold text-center">There is no document!</td>
            </tr>
        </tbody>
    </table>
</div>
</div>



        </div>
      </div>
    </div>
</div>
<!--  -->

            </div>
        </div>

        <user-form :isOpen="isFormOpen" :processing="processing" :isEdit="isFormEdit" :form="formObject" @formsave="saveUser(user)" @formclose="closeModal"></user-form>

    </AuthenticatedLayout>
</template>


<script>
    const defaultFormObject = {
        name: null, 
        fname:null,
        lname:null,
        photo:null,
    };

    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import UserForm from '@/Pages/Generic/User/Form.vue';
    // import PrimaryButton from '@/Components/PrimaryButton.vue';

    export default{
        props: [
            'user',
            'documents',
        ],
        components: {
            AuthenticatedLayout,
            UserForm
        },
        data()
        {
            return {
                isFormOpen: false,
                isFormEdit: false,
                formObject: defaultFormObject,
                avatar:'',
                processing:false,
            }
        },

        methods: {
            saveUser(user)
            {   
                let isOk = true;
                /*
                if(this.formObject.fname.length <= 2){
                    UserForm.errors.fname = "First name must have at least 3 character";
                    isOk = false;
                }
                if(this.formObject.lname.length <= 2){
                    this.formObject.errors.lname = "First name must have at least 3 character";
                    isOk = false;
                }
                */

                if(isOk){
                    let url = '/users';
                    if(user.id){
                        url = '/users/' + user.id;
                        user._method = 'PUT';
                    }
                    this.$inertia.post(url, user, {
                        onError: () => {
                            this.processing = false;
                        },
                        onSuccess: () => {
                            this.closeModal();
                            this.processing = false;
                        }
                    });
                }
            },
            closeModal()
            {
                this.isFormOpen = false;
                this.processing = false;
            },
            openForm(user)
            {
                this.isFormOpen = true;
                this.isFormEdit = !!user;
                this.formObject = user ? user : defaultFormObject;
                this.$page.props.errors = {};
            },

            changeStatus(id, status)
            {
                this.processing = true;
                this.$inertia.post(route('user.changestatus', {user:id}), {status:status}, {
                    onError: () => {
                        this.processing = false;
                    },
                    onSuccess: () => {
                        this.processing = false;
                    }
                });
            },

            deleteDocument(id)
            {   
                this.processing = true;
                this.$inertia.delete(route('documents.destroy', {id:id}), {
                    onError: () => {
                        this.processing = false;
                    },
                    onSuccess: () => {
                        this.processing = false;
                    }
                });
            },

            changePhoto(e){
                this.processing = true;
                this.$inertia.post(route('user.changephoto'), {photo:this.avatar}, {
                    onError: () => {
                        this.processing = false;
                    },
                    onSuccess: (res) => {
                        this.processing = false;
                        this.formObject.photo = res.props.user.photo;
                    }
                });

            },

            uploadDocument(e){       
                this.processing = true;         
                this.$inertia.post(route('documents.store'), {documents:this.documents}, {
                    onError: () => {
                        this.processing = false;
                    },
                    onSuccess: () => {
                        this.processing = false;
                    }
                });
                
            },

        }
    }
</script>