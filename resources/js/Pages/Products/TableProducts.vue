<template>
<div class="p-4 text-lg text-white bg-gray-800">
        <strong class="float-left">CATEGORÍAS</strong>

        <button :disabled="(true)?false:true" @click="removeProduct" class="float-right p-2 mr-4 text-white bg-red-500 rounded-sm hover:text-gray-800 hover:bg-red-400">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>

        </button>

        <button  @click="viewSort= !viewSort" class="float-right p-2 mr-4 hover:text-gray-600 bg-green-400 rounded-sm hover:text-gray-500 hover:bg-green-300">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 float-right">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3l-3 3" />
            </svg>

        </button>

        <button @click="addProduct" class="float-right p-2 mr-3 text-gray-800 bg-gray-300 rounded-sm hover:text-white hover:bg-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
        </button>


        <div class="clear-both"></div>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400" v-if="!viewSort">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Nombre
                    </th>
                    <th scope="col" class="px-6 py-3 text-center">
                        Precio
                    </th>

                    <th scope="col" class="px-6 py-3 text-center">
                        Carta Qr
                        <span @click="allQr" class="bg-gray-400 text-white p-2 rounded-sm cursor-pointer mr-1">Todo</span>
                        <span @click="inQr" class="bg-green-400 text-white p-2 rounded-sm cursor-pointer mr-1"> SI </span>
                        <span @click="outQr" class="bg-red-400 text-white p-2 rounded-sm cursor-pointer"> NO </span>
                    </th>
                </tr>
                </thead>
                <tbody>
                    <template v-for="(product, i ) in products" :key ="i">
                        <tr @click="edit(product)" :id="product.id" class="bg-white border-b cursor-pointer row dark:bg-gray-800 dark:border-gray-700 hover:bg-yellow-200 dark:hover:bg-gray-600" v-if="(viewQr =='all')?1:(viewQr == product.qr)">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ product.name  }}
                            </th>

                            <td class="px-6 py-4 text-center">
                                {{ product.products  }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span v-if="product.qr">si</span>
                                <span v-else>no</span>
                            </td>
                        </tr>
                    </template>
            </tbody>
        </table>

        <draggable v-model="productsSort" v-if="viewSort" @change="log">
            <div v-for="(products, i ) in productsSort" :key ="i" class="p-4 text-sm cursor-move bg-white border-b cursor-pointer row dark:bg-gray-800 dark:border-gray-700 hover:bg-yellow-200 dark:hover:bg-gray-600">
                <div class="grid grid-cols-12 lg:grid-cols-12">
                    <div  class="col-span-8" >
                        {{ products.name  }}
                    </div>
                    <div  class="col-span-4" >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 float-right">
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
import EventBus from '../../EventBus.js'
import {ref,onMounted, onUpdated} from 'vue';
import { VueDraggableNext } from 'vue-draggable-next'
const products = ref('');
const categoryActive = ref(0);
export default {
    setup(){

        EventBus.on('getProductsCategory', (category) =>{
            getProducts(category);
            categoryActive.value = category;
        });

        const getProducts = async(category) =>{
        await axios.post('get-products/'+category)
            .then(response=>{
                products.value = response.data;
            });
        }

        const addProduct = () =>{
            EventBus.emit('openNewProduct',categoryActive.value);
        }

        return {
            addProduct,
            products
        }
    }
    ,components:{
        draggable: VueDraggableNext,
    }
}
</script>
