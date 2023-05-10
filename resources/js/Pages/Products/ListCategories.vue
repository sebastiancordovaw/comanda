<template>
    <ul>
        <li class="text-white bg-gray-800" style="padding: 18px;"><strong>Productos</strong></li>
        <li  class="cursor-pointer"  v-for="(category, i) in categories" :key ="i"><p @click="openMenu(category.id)" class="pl-4 pt-4 pb-4 pr-4 bg-gray-600 text-white hover:bg-gray-800" :class="(categoryActive == category.id)?'active':''">{{ category.name }} <svg v-if="category.categories.length" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 mt-1 float-right">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 5.25l-7.5 7.5-7.5-7.5m15 6l-7.5 7.5-7.5-7.5" />
            </svg>
            </p>
            <ul v-if="categoryActive == category.id">
                <li  class="cursor-pointer" v-for="(cat, i) in category.categories" :key ="i"><p @click="getProducts(cat.id)" class="pl-10 pt-3 pb-3 pr-3 bg-gray-600 text-white hover:bg-gray-800">{{ cat.name }}</p></li>
            </ul>
        </li>
    </ul>
    </template>

    <script>
    import{ref, onMounted, onUpdated} from 'vue';
    import EventBus from "../../EventBus.js";
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

            const getCategories = async() =>{
            await axios.post('/get-categories')
                .then(response=>{
                    categories.value = response.data;

                    //////FormProducts.vue
                    EventBus.emit('sendCategories',response.data);
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
    background-color: #2ecc71 !important;
}
.active:hover{
    background-color: #27ae60 !important;
}
</style>

