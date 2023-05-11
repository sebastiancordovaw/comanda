<template>
<div v-if="viewUpdateCategory">
    <div class="p-4 text-lg text-white bg-red-500">
        <strong class="float-left">{{ name }}</strong>
        <div class="clear-both"></div>
    </div>

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

        <div class="sm:col-span-12" v-if="!categories.length" >
            <input
                class="relative float-left  mr-[6px] mt-[0.15rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-[0.125rem] border-solid border-neutral-300 outline-none before:pointer-events-none before:absolute before:h-[0.875rem] before:w-[0.875rem] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:-mt-px checked:after:ml-[0.25rem] checked:after:block checked:after:h-[0.8125rem] checked:after:w-[0.375rem] checked:after:rotate-45 checked:after:border-[0.125rem] focus:ring-orange-400 checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:shadow-none focus:transition-[border-color_0.2s] focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-[0.875rem] focus:after:w-[0.875rem] focus:after:rounded-[0.125rem] focus:after:content-[''] checked:focus:before:scale-100 checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] checked:focus:after:-mt-px checked:focus:after:ml-[0.25rem] checked:focus:after:h-[0.8125rem] checked:focus:after:w-[0.375rem] checked:focus:after:rotate-45 checked:focus:after:rounded-none checked:focus:after:border-[0.125rem] checked:focus:after:border-l-0 checked:focus:after:border-t-0 checked:focus:after:border-solid checked:focus:after:border-white checked:focus:after:bg-transparent disabled:opacity-60 dark:border-neutral-600 dark:checked:border-primary dark:checked:bg-primary dark:focus:before:shadow-[0px_0px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca]"
                type="checkbox"
                v-model="isSubCategory"
                :checked="isSubCategory"
                value = 1
                id="sub-category"
                />
            <label
                class="inline-block pl-[0.15rem]"
                for="sub-category">
                Sub-Categoría
            </label>
        </div>
        <div class="sm:col-span-12" v-if="isSubCategory">
            <label for="category_id" class="block font-medium leading-6 text-gray-900 text-md">Categoría Padre</label>
            <div class="mt-2">
                <select v-model="category_id" id="category_id" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-orange-400 sm:text-sm sm:leading-6">
                    <template v-for="(cat, i ) in selectCategories" :key="i">
                        <option v-if="id != cat.id" :value="cat.id">{{ cat.name }}</option>
                    </template>
                </select>
            </div>
        </div>

        <div class="sm:col-span-12">
            <input
                class="relative float-left  mr-[6px] mt-[0.15rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-[0.125rem] border-solid border-neutral-300 outline-none before:pointer-events-none before:absolute before:h-[0.875rem] before:w-[0.875rem] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:-mt-px checked:after:ml-[0.25rem] checked:after:block checked:after:h-[0.8125rem] checked:after:w-[0.375rem] checked:after:rotate-45 checked:after:border-[0.125rem] focus:ring-orange-400 checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:shadow-none focus:transition-[border-color_0.2s] focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-[0.875rem] focus:after:w-[0.875rem] focus:after:rounded-[0.125rem] focus:after:content-[''] checked:focus:before:scale-100 checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] checked:focus:after:-mt-px checked:focus:after:ml-[0.25rem] checked:focus:after:h-[0.8125rem] checked:focus:after:w-[0.375rem] checked:focus:after:rotate-45 checked:focus:after:rounded-none checked:focus:after:border-[0.125rem] checked:focus:after:border-l-0 checked:focus:after:border-t-0 checked:focus:after:border-solid checked:focus:after:border-white checked:focus:after:bg-transparent disabled:opacity-60 dark:border-neutral-600 dark:checked:border-primary dark:checked:bg-primary dark:focus:before:shadow-[0px_0px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca]"
                type="checkbox"
                v-model.number="qr"
                :checked="qr"
                value=1
                id="qr"
                />
            <label
                class="inline-block pl-[0.15rem]"
                for="qr">
                Carta Qr
            </label>
        </div>
    </div>
    <div class="pb-4 pl-4 mt-2 mb-2">
        <button @click = "save" class="p-2 text-white bg-green-400 rounded-sm hover:bg-green-500">Guardar</button>
        <div class="clear-both"></div>
    </div>
</div>
</template>
<script>
import {ref, onMounted, onUpdated} from 'vue';
import EventBus from "../../EventBus.js";
const errores = ref('');
const id = ref('');
const name = ref('');
const qr = ref(0);
const category_id = ref('');
const categories = ref([]);
const selectCategories = ref([]);
const viewUpdateCategory = ref(false);
const isSubCategory = ref(false);
export default {
    emits:['updateList','idRow'],
    setup(props,{emit}){

        onMounted(()=>{

        })

        EventBus.on('openUpdateCategory',(category)=>{
            viewUpdateCategory.value = true;
            set(category);
        });

        EventBus.on('openNewCategory',(category)=>{
            viewUpdateCategory.value = false;
        });

        EventBus.on('sendCategories',(categories)=>{
            selectCategories.value = categories;
        })

        const save = async() =>{
            let formData = new URLSearchParams();
            formData.append("id", id.value);
            formData.append("name", name.value);
            formData.append("category_id", category_id.value);
            formData.append("qr", qr.value);
            formData.append("isSubCategory", isSubCategory.value);

            const AxiosConfig = {
                method: 'PUT',
                url: '/updateCategory',
                data: formData,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                }
            };

            await axios(AxiosConfig)
            .then(response=>
            {
                ///////TableCategory.vue
                EventBus.emit('UpdateCategory',  response.data);
                errores.value = '';
            })
            .catch(error=>{
                if(error.response.status === 422)
                {
                    errores.value = error.response.data.errors;
                }
            })
        }

        const set = (category) =>
        {
            id.value = category.id;
            name.value = category.name;
            qr.value = category.qr;
            category_id.value = category.category_id;
            categories.value=category.categories;

            if(category_id.value)
            {
                isSubCategory.value = true;
            }
            else
            {
                isSubCategory.value = false;
            }

            if(category.qr)
            {
                qr.value = true;
            }
            else
            {
                qr.value = false;
            }
        }

        return {
            id,
            name,
            qr,
            category_id,
            categories,
            selectCategories,
            isSubCategory,
            save,
            errores,
            viewUpdateCategory
        }
    },
    components:{

    }
}
</script>
