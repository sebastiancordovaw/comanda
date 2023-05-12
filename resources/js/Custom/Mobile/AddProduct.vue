<template>
<div class="p-3 col-span-7 bg-gray-700">
    <input placeholder="Buscar Producto..." v-model="searchInput" type="text"  name="" id="" class="w-full border-t-0 border-b border-l-0 border-r-0 bg-transparent text-white border-gray-400 focus:ring-transparent focus:border-white">
</div>
<div class="grid grid-cols-12 " >
    <div  class="col-span-5">
        <div class="p-3 bg-gray-700 text-white font-bold shadow">CATEGORÍAS</div>

        <div v-for="(categoryf,i) in categories" :key = "i">
            <span class="pl-2 py-2 block text-sm rowFather" @click="getProduct(categoryf.key)" :id="categoryf.key">{{ categoryf.label }}</span>
            <div class="pl-4 py-2 text-sm rowSon" @click="getProduct(categorys.key)" :id="categorys.key" v-for="(categorys,i) in categoryf.children" :key = "i">
                {{ categorys.label }}
            </div>
        </div>
    </div>
    <div class="col-span-7 pr-2">

        <ul>
            <li v-for="(product, i) in products" :key ="i">
                <div class="grid grid-cols-12 " @click="addProduct(product)" >
                    <div  class="col-span-8 p-2">{{ product.name }}</div>
                    <div  class="col-span-4 text-right p-2">${{ product.price }}</div>
                </div>
            </li>
        </ul>

    </div>
</div>
<div class="rounded-full bg-green-400 p-4 text-white h-20 w-20" style="right: 5%; position: fixed; bottom: 5%;" @click="closeAddProductMobile">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12">
    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
    </svg>
</div>

<configCart1 :show="configCart1Viwe" :product="productSelect" @confirmConfigCart1 = "confirmConfigCart1" @CloseConfigCart1 = "configCart1ViweFunction" ></configCart1>
</template>

<script>
import { ref, onMounted } from 'vue'
import EventBus from '../../EventBus';
import configCart1 from './configCart1.vue';
const categories = ref([]);

const products = ref([]);
const productSelect = ref(false);
const configCart1Viwe = ref(false);
const searchInput = ref(null)
export default {
    emits:['closeAddProductMobile'],
    setup(props,{emit}) {

        onMounted(()=>
        {
            getCategorias()
        })

        const closeAddProductMobile=()=>{
            emit("closeAddProductMobile");
        }

        const getCategorias  = async() =>{
            await axios.post('/get-CategoriesTree')
            .then(response=>
            {
                categories.value = response.data.root;
            });
        }

        const getProduct = async(id) =>{
            for(let i = 0; i<document.getElementsByClassName("rowFather").length; i++)
            {
                document.getElementsByClassName("rowFather")[i].classList.remove("active");
            }
            for(let i = 0; i<document.getElementsByClassName("rowSon").length; i++)
            {
                document.getElementsByClassName("rowSon")[i].classList.remove("active");
            }
            document.getElementById(id).classList.add("active");

            await axios.post('/get-products/'+id)
            .then(response=>
            {
                products.value = response.data;
            });

        }

        const addProduct = (product) =>{
            productSelect.value = product;
            configCart1ViweFunction();
        }

        const configCart1ViweFunction = () =>{
            configCart1Viwe.value=!configCart1Viwe.value;
        }

        const confirmConfigCart1 = (data) =>{
            ////Custom/Cart1.vue
            configCart1Viwe.value=!configCart1Viwe.value;
            EventBus.emit("addProductMobileModal",{'product':productSelect.value,'data':data});

        }






        return {
            categories,
            getProduct,
            products,
            addProduct,
            productSelect,
            configCart1Viwe,
            configCart1ViweFunction,
            confirmConfigCart1,
            searchInput,
            closeAddProductMobile
        }
    },
    components:{
        configCart1
    }
}
</script>
<style scoped>
.shadow{
-webkit-box-shadow: inset 0px 4px 5px 0px rgba(0,0,0,0.75);
-moz-box-shadow: inset 0px 4px 5px 0px rgba(0,0,0,0.75);
box-shadow: inset 0px 4px 5px 0px rgba(0,0,0,0.75);
}
.active{
    background-color: #2c3e50;
    color: white;
}
</style>
