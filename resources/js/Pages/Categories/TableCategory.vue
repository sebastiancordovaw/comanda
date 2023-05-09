<template>
    <div class="p-4 text-lg text-white bg-gray-800">
        <strong class="float-left">CATEGORÍAS</strong>

        <button :disabled="(category.id)?false:true" @click="removeCategory" class="float-right p-2 mr-4 text-white bg-red-500 rounded-sm hover:text-gray-800 hover:bg-red-400">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>

        </button>

        <button  @click="viewSort= !viewSort" class="float-right p-2 mr-4 hover:text-gray-600 bg-green-400 rounded-sm hover:text-gray-500 hover:bg-green-300">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 float-right">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3l-3 3" />
            </svg>

        </button>

        <button @click="addCategory" class="float-right p-2 mr-3 text-gray-800 bg-gray-300 rounded-sm hover:text-white hover:bg-gray-400">
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
                        Carta Qr
                        <span @click="allQr" class="bg-gray-400 text-white p-2 rounded-sm cursor-pointer mr-1">Todo</span>
                        <span @click="inQr" class="bg-green-400 text-white p-2 rounded-sm cursor-pointer mr-1"> SI </span>
                        <span @click="outQr" class="bg-red-400 text-white p-2 rounded-sm cursor-pointer"> NO </span>
                    </th>
                    <th scope="col" class="px-6 py-3 text-center">
                        Productos
                    </th>

                </tr>
                </thead>
                <tbody>
                    <template v-for="(category, i ) in categories" :key ="i">
                        <tr @click="edit(category)" :id="category.id" class="bg-white border-b cursor-pointer row dark:bg-gray-800 dark:border-gray-700 hover:bg-yellow-200 dark:hover:bg-gray-600" v-if="(viewQr =='all')?1:(viewQr == category.qr)">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ category.name  }}
                            </th>
                            <td class="px-6 py-4 text-center">
                                <span v-if="category.qr">si</span>
                                <span v-else>no</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                {{ category.products  }}
                            </td>
                        </tr>
                        <template v-for="(cat , e ) in category.categories" :key="e" >
                        <tr @click="edit(cat)" :id="cat.id" class="bg-white border-b cursor-pointer row dark:bg-gray-800 dark:border-gray-700 hover:bg-yellow-200 dark:hover:bg-gray-600" v-if="(viewQr =='all')?1:(viewQr == cat.qr)">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="float-left w-4 h-4 mt-1 ml-4 mr-1 text-orange-800">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 4.5l7.5 7.5-7.5 7.5m-6-15l7.5 7.5-7.5 7.5" />
                                </svg>
                                {{ cat.name  }}
                            </th>
                            <td class="px-6 py-4 text-center">
                                <span v-if="cat.qr">si</span>
                                <span v-else>no</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                {{ cat.products.length  }}
                            </td>
                        </tr>
                        </template>
                    </template>

            </tbody>
        </table>


        <draggable v-model="categoriesSort" v-if="viewSort" @change="log">

            <div v-for="(category, i ) in categoriesSort" :key ="i" class="p-4 text-sm cursor-move bg-white border-b cursor-pointer row dark:bg-gray-800 dark:border-gray-700 hover:bg-yellow-200 dark:hover:bg-gray-600">
                <div class="grid grid-cols-12 lg:grid-cols-12">
                    <div  class="col-span-8" >
                        {{ category.name  }}
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

import {ref, onMounted, onUpdated} from 'vue';
import Swal from 'sweetalert2';
import { VueDraggableNext } from 'vue-draggable-next'
const categories = ref(null);
const categoriesSort = ref([]);
const category = ref('');
const list = ref(null);
const viewSort = ref(false);
const viewQr = ref('all');


import EventBus from "../../EventBus.js";

export default {
    emits:['sendCategory','sendCategories','addCategory','deleteCategory'],
    setup(props,{emit}){

        onMounted(()=>{
            getCategories();
        });

        onUpdated(()=>{

            EventBus.on('UpdateEventList', () =>{
                EventBus.on('affectRow', (row) =>{
                    getCategories(row);
                    getCategory(row);
                    next();
                });
            });


        });

        const log = async() =>{

            const json = JSON.stringify(categoriesSort.value);
            await axios.post('/updateSort/'+json, {
            headers: {
                // Overwrite Axios's automatically set Content-Type
                'Content-Type': 'application/json'
            }
            })
            .then(response=>
            {
                getCategories();
            })
            .catch(error=>{
                if(error.response.status === 422)
                {
                    console.log(error.response.data.error);
                }
            });
        }


        const inQr = () =>{
            viewQr .value = 1;
        }

        const outQr = () =>{
            viewQr .value = 0;
        }

        const allQr = () =>{
            viewQr .value = 'all';
        }

        const addCategory = () =>{
            emit('addCategory');
            getCategories();
        }

        const removeCategory = () =>{

            Swal.fire({
                        title:'Seguro de eliminar Categoría',
                        text: 'Esta accion no se podra revertir!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#27ae60',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, Eliminar'
                        }).then(async(result) =>
                        {
                        if (result.isConfirmed) {

                            let formData = new URLSearchParams();
                                formData.append("id", category.value.id);

                                const AxiosConfig = {
                                    method: 'delete',
                                    url: '/deleteCategory',
                                    data: formData,
                                    headers: {
                                        'Content-Type': 'application/x-www-form-urlencoded',
                                    }
                                };


                                await axios(AxiosConfig)
                                .then(response=>
                                {
                                    getCategories();
                                    emit("deleteCategory");
                                })
                                .catch(error=>{
                                    if(error.response.status === 422)
                                    {
                                        Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: error.response.data.error,
                                        confirmButtonColor: '#27ae60',
                                        });
                                    }
                                })
                        }
                        })
        }

        const getCategories = async(row = false) =>{
        await axios.post('/get-categories')
            .then(response=>{
                categories.value = response.data;
                const nuevoObject =[];
                for(let i = 0; i < Object.keys(response.data).length; i++)
                {
                    nuevoObject.push({"id":response.data[[Object.keys(response.data)[i]]].id,"name":response.data[[Object.keys(response.data)[i]]].name});
                }
                categoriesSort.value = nuevoObject;
                emit('sendCategories',response.data)
                if(row)
                {
                    setTimeout(() => {
                        activate(row);
                    }, 1);
                }
            });
        }

        const getCategory = async(row) =>{

                let formData = new URLSearchParams();
                formData.append("id", row);

                const AxiosConfig = {
                method: 'post',
                url: '/get-category',
                data: formData,
                headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                }
                };


                await axios(AxiosConfig)
                .then(response=>
                {
                    category.value = response.data
                })
                .catch(error=>{
                    if(error.response.status === 422)
                    {
                        Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error',
                        confirmButtonColor: '#27ae60',
                        });
                    }
                })
        }

        const edit =(categoryEntry) =>{
            category.value = categoryEntry;
            activate(categoryEntry);
            emit('sendCategory',categoryEntry);
            getCategories();
        }

        const activate = (categoryEntry) =>{
            for(let i = 0; i < document.getElementsByClassName("row").length; i++)
            {
                document.getElementsByClassName("row")[i].classList.remove("bg-yellow-200")
            }
            let row = (categoryEntry.id)?categoryEntry.id:categoryEntry;
            document.getElementById(row).classList.add("bg-yellow-200");
        }


        return {
            categories,
            edit,
            category,
            addCategory,
            removeCategory,
            categoriesSort,
            viewSort,
            list,
            log,
            inQr,
            outQr,
            allQr,
            viewQr
        }

    },
    components:{
        draggable: VueDraggableNext,
    },
    props:['updateListCategory','affectRow']
}
</script>
