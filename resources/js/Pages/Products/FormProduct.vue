
<template>
    <div v-if="viewUpdateProduct">
        <div class="p-4 text-lg text-white bg-gray-800">
            <strong class="float-left">{{ name }}</strong>
            <div class="clear-both"></div>
        </div>
        <div style="min-height: 60vh;">
        <div class="grid grid-cols-12 p-4 pb-4 mt-2 gap-y-2">

            <ul v-if="errores!=''"  class="col-span-12 p-4 text-white bg-red-400 rounded-sm ">
                <li v-for="(error,key) in errores" :key ="key">
                {{ error[0] }}
                </li>
            </ul>

            <div class="sm:col-span-12">
                <label for="name" class="block font-medium leading-6 text-gray-900 text-md">Nombre</label>
                <div class="mt-2">
                <input type="text" v-model="name" id="name"  class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-orange-400 sm:text-sm sm:leading-6">
                </div>
            </div>

            <div class="sm:col-span-12">
                <label for="name" class="block font-medium leading-6 text-gray-900 text-md">Categoría</label>
                <div class="mt-2">
                    <TreeSelect v-model="category_id" :options="selectCategories" placeholder="Select Item" class="w-full md:w-20rem" />
                </div>
            </div>

            <div class="sm:col-span-12">
                <label for="price" class="block font-medium leading-6 text-gray-900 text-md">Precio</label>
                <div class="mt-2">
                <input type="text" v-model.number="price" id="price"  class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-orange-400 sm:text-sm sm:leading-6">
                </div>
            </div>

            <div class="sm:col-span-12">
                <label for="description" class="block font-medium leading-6 text-gray-900 text-md">Descripción</label>
                <div class="mt-2">
                    <textarea style="resize: none;" v-model="description" id="description"  class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-orange-400 sm:text-sm sm:leading-6"></textarea>
                </div>
            </div>

            <div class="sm:col-span-12">
                <label for="qr" class="block font-medium leading-6 text-gray-900 text-md">Carta Qr</label>
                <select v-model="qr" id="qr" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-orange-400 sm:text-sm sm:leading-6">
                            <option value="category">Según Categoría</option>
                            <option value="yes">Si</option>
                            <option value="not">No</option>
                        </select>
            </div>

            <div class="sm:col-span-12">
                <input
                    class="relative float-left  mr-[6px] mt-[0.15rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-[0.125rem] border-solid border-neutral-300 outline-none before:pointer-events-none before:absolute before:h-[0.875rem] before:w-[0.875rem] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:-mt-px checked:after:ml-[0.25rem] checked:after:block checked:after:h-[0.8125rem] checked:after:w-[0.375rem] checked:after:rotate-45 checked:after:border-[0.125rem] focus:ring-orange-400 checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:shadow-none focus:transition-[border-color_0.2s] focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-[0.875rem] focus:after:w-[0.875rem] focus:after:rounded-[0.125rem] focus:after:content-[''] checked:focus:before:scale-100 checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] checked:focus:after:-mt-px checked:focus:after:ml-[0.25rem] checked:focus:after:h-[0.8125rem] checked:focus:after:w-[0.375rem] checked:focus:after:rotate-45 checked:focus:after:rounded-none checked:focus:after:border-[0.125rem] checked:focus:after:border-l-0 checked:focus:after:border-t-0 checked:focus:after:border-solid checked:focus:after:border-white checked:focus:after:bg-transparent disabled:opacity-60 dark:border-neutral-600 dark:checked:border-primary dark:checked:bg-primary dark:focus:before:shadow-[0px_0px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca]"
                    type="checkbox"
                    v-model="status"
                    value = 1
                    id="status"
                    />
                <label
                    class="inline-block pl-[0.15rem]"
                    for="sub-category">
                    Estado
                </label>
            </div>

        </div>
        <div class="pb-4 pl-4 mt-2 mb-2">
            <button @click = "save" class="p-2 text-white bg-green-400 rounded-sm hover:bg-green-500">Guardar</button>
            <div class="clear-both"></div>
        </div>
        </div>
    </div>
    </template>

    <script>
    import EventBus from '../../EventBus.js';
    import {ref,onMounted, onUpdated, onUnmounted} from 'vue';
    import TreeSelect from 'primevue/treeselect';
    const viewUpdateProduct = ref(false);
    const errores = ref('');

    const id = ref(0);
    const name = ref('');
    const category_id = ref(0);
    const price = ref('');
    const description = ref('');
    const qr = ref('category');
    const status = ref(true);
    const selectCategories = ref('');
    let nodeService = '';
    import NodeService from '../../NodeService';
    export default {
        setup()
        {
            onMounted(()=>{
                nodeService = new NodeService();
                nodeService.getTreeNodes().then((data) => (selectCategories.value = data));
            })

            onUnmounted(()=>{
                reset();
            })

            EventBus.on('openNewProduct', (category) =>{
                viewUpdateProduct.value = false;
            });

            EventBus.on('openUpdateProduct', (product) =>{
                viewUpdateProduct.value = true;
                console.log(product);
                set(product);
            });

            const save = async()=>{
                let formData = new URLSearchParams();
                formData.append("id", id.value);
                formData.append("name", name.value);
                formData.append("category_id", Object.keys(category_id.value)[0]);
                formData.append("price", price.value);
                formData.append("description", description.value);
                formData.append("qr", qr.value);
                formData.append("status", status.value);
                const AxiosConfig = {
                    method: 'PUT',
                    url: '/updateProducto',
                    data: formData,
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    }
                };

                await axios(AxiosConfig)
                .then(response=>
                {
                    /////TableProducts
                    EventBus.emit('UpdateProduct',response.data);
                    errores.value = "";
                })
                .catch(error=>{
                    if(error.response.status === 422)
                    {
                        errores.value = error.response.data.errors;
                    }
                    else if(error.response.status === 500){
                        console.log(error.response.data.errors);
                    }
                })
            }


            const set = (product) => {
                id.value =product.id;
                name.value =product.name;

                const cadena = '{"'+product.category_id+'":true}';
                category_id.value =JSON.parse(cadena);

                price.value =product.price;
                description.value = product.description;
                qr.value =product.qr;
                if(product.status)
                {
                    status.value = true;
                }
                else
                {
                    status.value = false;
                }

            }

            const reset = () => {
                viewUpdateProduct.value = false;
                errores.value = '';
                id.value = 0;
                name.value = ''
                category_id.value = 0;
                price.value ='';
                description.value = '';
                qr.value = 'category';
                status.value = true;
                selectCategories.value = '';
            }


            return {
                viewUpdateProduct,
                errores,
                id,
                name,
                category_id,
                price,
                description,
                qr,
                status,
                selectCategories,
                TreeSelect,
                save
            }
        }
        ,components:{

        }
    }
    </script>
