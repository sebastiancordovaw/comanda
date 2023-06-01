<template>
<div class="p-4 text-lg text-white bg-gray-800">
        <strong class="float-left">PRODUCTOS</strong>

        <button :disabled="(productActivate)?false:true" @click="removeProduct" class="float-right p-2 mr-4 text-white bg-red-500 rounded-sm hover:text-whte hover:bg-red-400">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>

        </button>

        <button  @click="viewSort= !viewSort" class="float-right p-2 mr-3 bg-green-400 rounded-sm hover:text-gray-600 hover:text-gray-500 hover:bg-green-300">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="float-right w-3 h-3">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3l-3 3" />
            </svg>

        </button>

        <button @click="addProduct" class="float-right p-2 mr-3 text-gray-900 bg-gray-300 rounded-sm hover:text-white hover:bg-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
        </button>


        <div class="clear-both"></div>
    </div>

    <div class="relative overflow-x-hidden" style="min-height: 60vh;">
        <table class="w-full min-h-full overflow-x-hidden text-sm text-left text-gray-500 dark:text-gray-400" v-if="!viewSort">
            <thead class="overflow-x-hidden text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 ">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Nombre
                    </th>
                    <th scope="col" class="px-6 py-3 text-center">
                        Precio
                    </th>

                    <th scope="col" class="px-6 py-3 text-center">
                        <Dropdown align="left" width="100%" :contentClasses="['py-0']">
                            <template #trigger>
                                <div class="relative mx-auto text-gray-600">
                                    <label class="p-2 text-white bg-gray-400 rounded-sm"> {{ (filterQrActive=="all")?'Carta Qr':(filterQrActive=='yes')?'Carta Qr (Si)':(filterQrActive=="not")?'Carta Qr (No)':'Qr Según Categoría' }}</label>
                                </div>
                            </template>

                            <template #content>
                                <a  class="text-left">
                                    <div class="grid grid-cols-12 px-2 py-2 hover:bg-gray-800 hover:text-white" @click.prevent = "filterQr('all')">
                                        <div class="col-span-9 cursor-pointer">Todos</div>
                                    </div>
                                    <div class="grid grid-cols-12 px-2 py-2 hover:bg-gray-800 hover:text-white" @click.prevent = "filterQr('category')">
                                        <div class="col-span-9 cursor-pointer">Según Categoría</div>
                                    </div>
                                    <div class="grid grid-cols-12 px-2 py-2 hover:bg-gray-800 hover:text-white" @click.prevent = "filterQr('yes')">
                                        <div class="col-span-9 cursor-pointer">Si</div>
                                    </div>
                                    <div class="grid grid-cols-12 px-2 py-2 hover:bg-gray-800 hover:text-white" @click.prevent = "filterQr('not')">
                                        <div class="col-span-9 cursor-pointer">No</div>
                                    </div>
                                </a>
                            </template>
                        </Dropdown>

                    </th>
                    <th scope="col" class="px-6 py-3 text-center">

                        <Dropdown align="left" width="100%" :contentClasses="['py-0']">
                            <template #trigger>
                                <div class="relative mx-auto text-gray-600">
                                    <label class="p-2 text-white bg-gray-400 rounded-sm">{{ (filterStatusActive=="all")?'Estado':(filterStatusActive==1)?'Activo':'Inactivo' }}</label>
                                </div>
                            </template>

                            <template #content>
                                <a  class="text-left hover:bg-yellow-300">
                                    <div class="grid grid-cols-12 px-2 py-2 hover:bg-gray-800 hover:text-white" @click.prevent = "filterStatus('all')">
                                        <div class="col-span-9 cursor-pointer">Todos</div>
                                    </div>
                                    <div class="grid grid-cols-12 px-2 py-2 hover:bg-gray-800 hover:text-white" @click.prevent = "filterStatus(1)">
                                        <div class="col-span-9 cursor-pointer">Activo</div>
                                    </div>
                                    <div class="grid grid-cols-12 px-2 py-2 hover:bg-gray-800 hover:text-white" @click.prevent = "filterStatus(0)">
                                        <div class="col-span-9 cursor-pointer">Inactivo</div>
                                    </div>
                                </a>
                            </template>
                        </Dropdown>
                    </th>
                </tr>
                </thead>
                <tbody>

                    <template v-for="(product, i ) in products" :key ="i" >
                        <template v-if="(filterStatusActive=='all')?true:(filterStatusActive==product.status)">
                        <template v-if="(filterQrActive=='all')?true:(filterQrActive==product.qr)">
                        <tr @click="edit(product)" :id="product.id" class="bg-white border-b cursor-pointer hover:shadow-lg row dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ product.name  }}
                            </th>

                            <td class="px-6 py-4 text-center">
                                ${{ product.price  }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span v-if="(product.qr=='yes')">si</span>
                                <span v-if="(product.qr=='not')">no</span>
                                <span v-if="(product.qr=='category')">Según Catgoría</span>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span v-if="(product.status=='1')">Activo</span>
                                <span v-else>Inactivo</span>
                            </td>
                        </tr>
                        </template>
                        </template>
                    </template>
            </tbody>
        </table>

        <draggable v-model="productsSort" v-if="viewSort" @change="log">
            <div v-for="(products, i ) in productsSort" :key ="i" class="p-4 text-sm bg-white border-b cursor-move row dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600">
                <div class="grid grid-cols-12 lg:grid-cols-12">
                    <div  class="col-span-8" >
                        {{ products.name  }}
                    </div>
                    <div  class="col-span-4" >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="float-right w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3l-3 3" />
                        </svg>
                        <div class="clear-both"></div>
                    </div>
                </div>
            </div>
        </draggable>

    </div>
</template>
<script>
import Dropdown from '@/Components/Dropdown.vue';
import EventBus from '../../EventBus.js'
import {ref,onMounted, onUpdated} from 'vue';
import { VueDraggableNext } from 'vue-draggable-next';
import Swal from 'sweetalert2';
const products = ref('');
const categoryActive = ref(0);
const productActivate = ref(false)
const filterStatusActive = ref('all');
const filterQrActive = ref('all');
const viewSort = ref(false);
const productsSort = ref([]);
export default {
    setup(){

        onMounted(()=>{

        })

        EventBus.on('getProductsCategory', (category) =>{
            getProducts(category);
            categoryActive.value = category;
        });

        EventBus.on('NewProduct', (producto) =>{
            getProducts(categoryActive.value ,producto.id);
        });

        EventBus.on('UpdateProduct', (producto) =>{
            getProducts(categoryActive.value ,producto);
        });

        const getProducts = async(product,id=false) =>{
        await axios.post('get-products/'+product)
            .then(response=>{
                products.value = response.data;

                const nuevoObject =[];
                for(let i = 0; i < Object.keys(response.data).length; i++)
                {
                    if(response.data[[Object.keys(response.data)[i]]].status == 1)
                    {
                        nuevoObject.push({"id":response.data[[Object.keys(response.data)[i]]].id,"name":response.data[[Object.keys(response.data)[i]]].name});
                    }

                }
                productsSort.value = nuevoObject;


                if(id)
                {
                    setTimeout(() => {
                        activate(id);
                    }, 1);

                }
                else
                {
                    for(let i = 0; i < document.getElementsByClassName("row").length; i++)
                    {
                        document.getElementsByClassName("row")[i].classList.remove("bg-cyan-200")
                    }
                }
            });
        }

        const activate = (productoEntry) =>{
            console.log(productoEntry,"entra un producto");
            productActivate.value = productoEntry
            for(let i = 0; i < document.getElementsByClassName("row").length; i++)
            {
                document.getElementsByClassName("row")[i].classList.remove("bg-cyan-200")
            }
            document.getElementById(productoEntry).classList.add("bg-cyan-200");
        }

        const addProduct = () =>{
            /////////FormNewProduct.vue
            /////////ListCategories.vue
            EventBus.emit('openNewProduct',categoryActive.value);
        }

        const edit = (product) =>{
            /////////FormNewProduct.vue
            /////////FormProduct.vue
            EventBus.emit('openUpdateProduct',product);
            activate(product.id)
        }

        const removeProduct = () =>{

        Swal.fire({
            title:'Seguro de eliminar El producto',
            text: 'Esta accion no se podra revertir!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#111827',
            cancelButtonColor: '#06B6D4',
            confirmButtonText: 'Si, Eliminar'
            }).then(async(result) =>
            {
            if (result.isConfirmed) {

                let formData = new URLSearchParams();
                    formData.append("id", productActivate.value);

                    const AxiosConfig = {
                        method: 'delete',
                        url: '/deleteProduct',
                        data: formData,
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        }
                    };

                    await axios(AxiosConfig)
                    .then(response=>
                    {
                        getProducts(categoryActive.value,false);
                        productActivate.value = false;
                        /////////FormProduct.vue
                        EventBus.emit('openNewProduct',categoryActive.value);

                    })
                    .catch(error=>{
                        if(error.response.status === 422)
                        {
                            Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'No se puede eliminar, Debido a que este producto ya ha tenido transacciones',
                            confirmButtonColor: '#111827',
                            });
                        }
                    })
                }
            })
        }

        const filterStatus = (value) =>{
            filterStatusActive.value = value;
        }

        const filterQr = (value) =>{
            filterQrActive.value = value;
        }

        const log = async() =>{

            const json = JSON.stringify(productsSort.value);
            await axios.post('/updateSortProduct/'+json, {
            headers: {
                // Overwrite Axios's automatically set Content-Type
                'Content-Type': 'application/json'
            }
            })
            .then(response=>
            {
                getProducts(categoryActive.value);
            })
            .catch(error=>{
                if(error.response.status === 422)
                {
                    console.log(error.response.data.error);
                }
            });
        }


        return {
            addProduct,
            products,
            edit,
            removeProduct,
            productActivate,
            filterStatus,
            filterStatusActive,
            filterQr,
            filterQrActive,
            viewSort,
            productsSort,
            log

        }
    }
    ,components:{
        draggable: VueDraggableNext,Dropdown
    }
}
</script>
