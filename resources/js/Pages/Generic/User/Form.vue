<template>
    <div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400" v-if="isOpen">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

          <div class="bg-red-100 border-t-4 border-red-500 rounded-b text-red-900 px-4 py-3 shadow-md my-3" role="alert" v-if="$page.props.flash.errorModal">
              <div>
                  <div v-if="$page.props.flash.errorModal" v-for="(flashErr, flashErrKey) in $page.props.flash.error">
                      <p class="text-sm">{{ flashErr[0] }}</p>
                  </div>
              </div>
          </div>


            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>​
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                role="dialog" aria-modal="true" aria-labelledby="modal-headline">


                <form enctype="multipart/form-data">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="">

                            <div class="mb-4">
                                <label for="fname"
                                       class="borderlock text-gray-700 text-sm font-bold mb-2">First Name:</label>
                                <input type="text"
                                       v-model="form.fname"
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                       id="fname" placeholder="First Name">
                                <div v-if="$page.props.errors.fname" class="text-red-500">{{ $page.props.errors.fname }}</div>

                            </div>

                            <div class="mb-4">
                                <label for="lname"
                                       class="block text-gray-700 text-sm font-bold mb-2">Last Name:</label>
                                <input type="text"
                                       v-model="form.lname"
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                       id="lname" placeholder="Last Name">
                                <div v-if="$page.props.errors.lname" class="text-red-500">{{ $page.props.errors.lname }}</div>

                            </div>

                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                          <button type="button"
                                   v-show="isEdit"
                                   @click="$emit('formsave', form)"
                                   class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5"
                          >
                            Update
                          </button>
                        </span>
                        <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                          <button
                              type="button"
                              @click="$emit('formclose')"
                              class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                            Cancel
                          </button>
                        </span>
                    </div>
                </form>

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['form', 'isOpen', 'isEdit']
    }


</script>
