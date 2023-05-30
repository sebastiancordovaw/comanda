<template>
    <ul>
        <li class="text-white bg-gray-800" style="padding: 18px;"><strong>CATEGORÍAS</strong></li>
        <li  class="cursor-pointer"  v-for="(category, i) in categories" :key ="i"><p @click="openMenu(category.id)" class="pt-4 pb-4 pl-4 pr-4 text-white bg-gray-600 hover:bg-gray-800" :class="(categoryActive == category.id)?'active':''">{{ category.name }}
            <svg v-if="category.categories.length && categoryActive == category.id" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="float-right w-3 h-3 mt-1">
            <path  stroke-linecap="round" stroke-linejoin="round" d="M19.5 5.25l-7.5 7.5-7.5-7.5m15 6l-7.5 7.5-7.5-7.5" />
            </svg>

            <svg  v-if="category.categories.length && categoryActive != category.id" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="float-right w-3 h-3 mt-1">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 4.5l7.5 7.5-7.5 7.5m-6-15l7.5 7.5-7.5 7.5" />
            </svg>

            </p>
            <ul v-if="categoryActive == category.id">
                <li  class="cursor-pointer" v-for="(cat, i) in category.categories" :key ="i"><p @click="getProducts(cat.id)" class="pt-3 pb-3 pl-10 pr-3 text-white bg-gray-600 hover:bg-gray-800" :class="(categorySearch ==cat.id )?'childActive':''">{{ cat.name }}</p></li>
            </ul>
        </li>
    </ul>
    </template>

    <script>
    import{ref, onMounted, onUpdated} from 'vue';
    import EventBus from "../../EventBus.js";
    import FormNewProductVue from './FormNewProduct.vue';
    const categories = ref('');
    const categoryActive = ref(0);
    const categorySearch = ref(0);
    export default {
        setup() {

            onMounted(()=>{
                getCategories();
            })

            const openMenu = (category) =>{
                categoryActive.value = category;
                getProducts(category);
            }

            const getProducts = (category) =>{
                categorySearch.value = category;
                /////tableProducts.vue
                EventBus.emit('getProductsCategory',category);
            }

            EventBus.on('openNewProduct', (category) =>{

                 /////////////FormNewProduct.vue
                EventBus.emit('CategoryActive',categorySearch.value);
            });

            const getCategories = async() =>{
            await axios.post('/get-categories')
                .then(response=>{
                    categories.value = response.data;
                });
            }

            return {
                categories,
                categoryActive,
                getProducts,
                categorySearch,
                openMenu
            }
        },
    }
    </script>
<style scoped>
.active{
    background-color: #7bed9f !important;
}
.active:hover{
    background-color: #111827 !important;
}

.childActive{
    color: #7bed9f !important;
}
.childActive:hover{
    color: #111827 !important;
}
</style>

